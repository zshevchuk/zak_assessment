<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Contracts\DataProcessor;
use App\Lib\FileParser\Contracts\Line;
use App\Lib\FileParser\Contracts\LineIterator;
use App\Lib\FileParser\Enums\LogEnum;

class LogDataProcessor implements DataProcessor, LineIterator
{
    public array $peopleHashMap = [];
    public array $booksCheckoutMap = [];
    public array $booksTransactionTimeMap = [];

    public bool $initialized = false;

    public function initialize(): void
    {
        $this->initialized = true;
    }

    public function isInitialized(): bool
    {
        return $this->initialized === true;
    }

    public function iterateOverLine(Line $line): void
    {
        $data = (array)$line;

        $personId = $data['personId'];
        $bookId = $data['bookId'];
        $bookTimestamp = $data['timestamp'];
        $actionType = $data['actionType'];

        /**
         * which person has the most checkouts (which person_id)**
         */
        if ($actionType === LogEnum::CHECK_OUT) {
            if (isset($this->peopleHashMap[$personId])) {
                // increment amount of checkouts for a person
                $this->peopleHashMap[$personId]++;
            } else {
                // initialize value
                $this->peopleHashMap[$personId] = 1;
            }
        }

        /**
         *  how many books are checked out at this moment
         */
        if (isset($this->booksCheckoutMap[$bookId])) {
            unset($this->booksCheckoutMap[$bookId]);
        } else {
            $this->booksCheckoutMap[$bookId] = $personId;
        }

        /**
         * which book was checked out the longest time in total (summed up over all
         * transactions)
         */
        $this->booksTransactionTimeMap[$bookId][] = $bookTimestamp;

        $this->initialize();
    }

    /**
     * Processes "hash" maps and returns result
     */
    public function response(): array
    {
        $personWithMostBooks = $this->getPersonWithMostBooks();
        $bookWithLongestCheckout = $this->getBookWithLongestCheckoutTime();
        $checkoutedBooks = $this->getCurrentCheckoutedBooksCount();
        $maxCheckOutPeople = $this->getPersonWithMostCheckouts();

        return [
            'most_check_outed_people' => $maxCheckOutPeople,
            'book_with_longest_checkout' => $bookWithLongestCheckout,
            'books_checkouted_now' => $checkoutedBooks,
            'person_with_largest_number_of_books' => $personWithMostBooks,
        ];
    }

    public function getPersonWithMostCheckouts(): array
    {
        return $this->findKeysOfTheMaxValue($this->peopleHashMap);
    }

    public function getCurrentCheckoutedBooksCount(): int
    {
        return count($this->booksCheckoutMap);
    }

    /**
     *  which book was checked out the longest time in total (summed up over all
     * transactions)
     */
    public function getBookWithLongestCheckoutTime(): array
    {
        $now = new \DateTime('now');
        $now = $now->format('U');

        foreach ($this->booksTransactionTimeMap as $key => $transactions) {
            $transactionPairs = array_chunk($transactions, 2);
            $total = 0;

            foreach ($transactionPairs as $pair) {
                $startDate = $pair[0];
                $endData = $pair[1] ?? $now;

                $total += $endData - $startDate;
            }

            $output[$key] = $total;
        }

        return $this->findKeysOfTheMaxValue($output);
    }


    public function getPersonWithMostBooks(): array
    {
        $countOfBooksPerPerson = [];

        foreach ($this->booksCheckoutMap as $personId) {
            if (isset($countOfBooksPerPerson[$personId])) {
                $countOfBooksPerPerson[$personId]++;
            } else {
                $countOfBooksPerPerson[$personId] = 1;
            }
        }

        return $this->findKeysOfTheMaxValue($countOfBooksPerPerson);
    }

    /**
     * Find a key/keys of max value in the array
     */
    public function findKeysOfTheMaxValue(array $data): array
    {
        // extract element key with a max value
        // due to https://wiki.php.net/rfc/string_to_number_comparison we cannot just use max() safely to find biggest element
        $keys = [];
        $max = 0;

        foreach ($data as $key => $value) {
            if (!is_int($value)) continue;

            if ($value > $max) {
                $max = $value;
                $keys = [$key];
            } elseif ($value === $max) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

}


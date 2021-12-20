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
    }

    /**
     * Processes "hash" maps and returns result
     */
    public function response(): array
    {
        $personWithMostBooks = $this->getPersonWithMostBooks();
        $bookWithLongestCheckout = $this->getBookWithLongestCheckoutTime();
        $checkoutedBooks = $this->getCurrentCheckoutedBooksCount();
        $maxCheckOutPersonId = $this->getPersonWithMostCheckouts();

        return [
            'most_check_out_person_id' => $maxCheckOutPersonId,
            'book_with_longest_checkout' => $bookWithLongestCheckout,
            'books_checkouted_now' => $checkoutedBooks,
            'person_with_largest_number_of_books' => $personWithMostBooks,
        ];
    }

    protected function getPersonWithMostCheckouts(): int
    {
        return $this->findKeyOfTheMaxValue($this->peopleHashMap);
    }

    protected function getCurrentCheckoutedBooksCount(): int
    {
        return count($this->booksCheckoutMap);
    }

    /**
     *  which book was checked out the longest time in total (summed up over all
     * transactions)
     */
    protected function getBookWithLongestCheckoutTime(): int|string
    {
        $now = time();

        foreach ($this->booksTransactionTimeMap as &$transactions) {
            $transactionPairs = array_chunk($transactions, 2);
            $total = 0;

            foreach ($transactionPairs as $pair) {
                $startDate = $pair[0];
                $endData = $pair[1] ?? $now;

                $total += $endData - $startDate;
            }

            $transactions = $total;
        }

        return $this->findKeyOfTheMaxValue($this->booksTransactionTimeMap);
    }


    protected function getPersonWithMostBooks(): int
    {
        $countOfBooksPerPerson = [];

        foreach ($this->booksCheckoutMap as $personId) {
            if (isset($countOfBooksPerPerson[$personId])) {
                $countOfBooksPerPerson[$personId]++;
            } else {
                $countOfBooksPerPerson[$personId] = 1;
            }
        }

        return $this->findKeyOfTheMaxValue($countOfBooksPerPerson);
    }

    public function findKeyOfTheMaxValue(array $data): int|string
    {
        // extract element key with a max value
        $maxValue = max($data);

        $key = array_search($maxValue, $data);

        return $key;

    }
}


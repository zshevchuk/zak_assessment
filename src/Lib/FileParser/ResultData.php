<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Enums\LogEnum;

class ResultData
{
    public $peopleHashMap = [];
    public $booksCheckoutMap = [];
    public $booksTransactionTimeMap = [];

    /**
     * Iterate over a single line, collect some middle data for the end result
     */
    public function feedLine(Line $line)
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
     *
     * @return array
     */
    public function response(): array
    {
        $now = time();
        $countOfBooksPerPerson = [];

        /**
         * - who currently has the largest number of books (which person_id)
         */
        foreach ($this->booksCheckoutMap as $personId) {
            if (isset($countOfBooksPerPerson[$personId])) {
                $countOfBooksPerPerson[$personId]++;
            } else {
                $countOfBooksPerPerson[$personId] = 1;
            }
        }

        /**
         *  which book was checked out the longest time in total (summed up over all
         * transactions)
         */
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

// extract key with a max element
        $value = max($this->peopleHashMap);
        $maxCheckOutPersonId = array_search($value, $this->peopleHashMap);

// extract key with a max element
        $value = max($this->booksTransactionTimeMap);
        $bookWithLongestCheckout = array_search($value, $this->booksTransactionTimeMap);

// extract key with a max element
        $value = max($countOfBooksPerPerson);
        $personWithMostBooks = array_search($value, $countOfBooksPerPerson);

        return [
            'most_check_out_person_id' => $maxCheckOutPersonId,
            'book_with_longest_checkout' => $bookWithLongestCheckout,
            'books_checkouted_now' => count($this->booksCheckoutMap),
            'person_with_largest_number_of_books' => $personWithMostBooks,
        ];
    }
}


<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PollVotesFixture
 *
 */
class PollVotesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'poll_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'option_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'option_id' => ['type' => 'index', 'columns' => ['option_id'], 'length' => []],
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['poll_id', 'user_id', 'option_id'], 'length' => []],
            'poll_votes_ibfk_1' => ['type' => 'foreign', 'columns' => ['option_id'], 'references' => ['poll_options', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'poll_votes_ibfk_2' => ['type' => 'foreign', 'columns' => ['poll_id'], 'references' => ['polls', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'poll_votes_ibfk_3' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'poll_id' => 1,
                'user_id' => 1,
                'option_id' => 1
            ],
        ];
        parent::init();
    }
}

<?php
/**
 * rzeka.net framework (http://framework.rzeka.net/)
 *
 * @link http://framework.rzeka.net/
 * @copyright (c) 2013, rzeka.net
 * @license http://framework.rzeka.net/license New BSD License
 */

namespace RzekaE\Akismet;

use \InvalidArgumentException;
use RzekaE\Akismet\Connector;

class Akismet
{
    /**
     * Akismet library version
     */
    const LIB_VERSION = '1.0.1';

    /**
     * Application name and version
     */
    const APP_VERSION = 'rzeka.net/1.0.0';

    /**
     * Holds connector interface
     *
     * @var \RzekaE\Akismet\Connector\ConnectorInterface
     */
    private $connection = null;

    /**
     * Class constructor sets up connector
     *
     * @param RzekaE\Akismet\Connector\ConnectorInterface $connection Connection instance
     */
    public function __construct(Connector\ConnectorInterface $connection = null)
    {
        if ($connection === null) {
            $this->connection = new Connector\Curl();
        } else {
            $this->connection = $connection;
        }

        $this->connection->setUserAgent(sprintf('%s | Akismet/%s', self::APP_VERSION, self::LIB_VERSION));
    }

    /**
     * Allows to manually check if API key is valid or not
     *
     * @param string $apiKey Akismet API key
     * @param string $url The front page or home URL of the instance making the request
     * @throws InvalidArgumentException
     * @return boolean
     */
    public function keyCheck($apiKey = null, $url = null)
    {
        if ($apiKey === null || $url === null) {
            throw new InvalidArgumentException('Both apiKey and site URL cannot be null');
        }

        return $this->connection->keyCheck($apiKey, $url);
    }

    /**
     * Check if message is spam or not
     *
     * @param array $comment Message data. Required keys:<br />
     *      premalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     * @return boolean True if message is spam, false otherwise
     */
    public function check(array $comment)
    {
        return $this->connection->check($comment);
    }

    /**
     * Marks message as spam
     *
     * @param array $comment Message data. Required keys:<br />
     *      premalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     * @return boolean True if message has been marked as spam
     */
    public function sendSpam(array $comment)
    {
        return $this->connection->sendSpam($comment);
    }

    /**
     * Marks message as ham (not-spam)
     *
     * @param array $comment Message data. Required keys:<br />
     *      premalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     * @return boolean True if messahe has been marked as ham
     */
    public function sendHam(array $comment)
    {
        return $this->connection->sendHam($comment);
    }

    /**
     * Gets last error occurred
     *
     * @return string Returns null if there's no error
     */
    public function getError()
    {
        return $this->connection->getError();
    }
}

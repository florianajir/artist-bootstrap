<?php
/**
 * This file is part of the artist-bootstrap project.
 *
 * (c) Florian Ajir <http://github.com/florianajir/artist-bootstrap>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajir\ContactBundle\Service;

use Ajir\ContactBundle\Exception\EmailFailureException;
use Ajir\ContactBundle\Exception\EmailNotSentException;
use Swift_Mailer;
use Swift_Message;

/**
 * @author Florian Ajir
 */
class Mailer
{
    const CONTENT_TYPE = 'text/html';

    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $recipient;

    /**
     * @param Swift_Mailer $mailer
     *
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $recipient
     */
    public function setConfig($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Send email
     *
     * @param string $subject
     * @param string $body
     * @param string $from
     * @param string $contentType
     *
     * @throws EmailFailureException
     * @throws EmailNotSentException
     */
    public function send($subject, $body, $from, $contentType = self::CONTENT_TYPE)
    {
        $message = Swift_Message::newInstance($subject, $body, $contentType);
        $message
            ->setFrom($from)
            ->setTo($this->recipient);
        $failures = array();
        $sent = $this->mailer->send($message, $failures);
        if ($sent === 0) {
            throw new EmailNotSentException();
        }
        if (count($failures) > 0) {
            throw EmailFailureException::createFromArray($failures);
        }
    }
}

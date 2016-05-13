<?php
/**
 * This file is part of the artist-bootstrap project.
 *
 * (c) Florian Ajir <http://github.com/florianajir/artist-bootstrap>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajir\ContactBundle\Exception;

/**
 * @author Florian Ajir <florianajir@gmail.com>
 */
class EmailNotSentException extends EmailSubmitException
{
    const DEFAULT_MESSAGE = 'Email not sent';

    /**
     * @param string $message
     */
    public function __construct($message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}

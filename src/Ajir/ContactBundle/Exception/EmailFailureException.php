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
class EmailFailureException extends EmailSubmitException
{
    /**
     * @param array $failures
     *
     * @return EmailFailureException
     */
    public static function createFromArray(array $failures)
    {
        $message = implode(',', $failures);

        return new self($message);
    }
}

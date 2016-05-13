<?php
/**
 * This file is part of the artist-bootstrap project.
 *
 * (c) Florian Ajir <http://github.com/florianajir/artist-bootstrap>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajir\ContactBundle\Form;

use Ajir\ContactBundle\Exception\EmailFailureException;
use Ajir\ContactBundle\Exception\EmailNotSentException;
use \Swift_Mailer as Mailer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * The ContactHandler.
 * Use for manage your form submitions
 *
 * @author Florian Ajir
 */
class ContactHandler
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $recipient;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form $form
     * @param Mailer $mailer
     *
     */
    public function __construct(Form $form, Mailer $mailer)
    {
        $this->form = $form;
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
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Process form
     *
     * @return bool
     *
     * @throws EmailFailureException
     * @throws EmailNotSentException
     */
    public function process(Request $request)
    {
        if ('POST' === $request->getMethod()) {
            $this->form->submit($request);
            if ($this->form->isValid()) {
                $contact = $this->form->getData();
                $this->onSuccess($contact);

                return true;
            }
        }

        return false;
    }

    /**
     * Send mail on success
     *
     * @param Contact $contact
     *
     * @throws EmailFailureException
     * @throws EmailNotSentException
     */
    private function onSuccess(Contact $contact)
    {
        $message = \Swift_Message::newInstance(
            $contact->getSubject(),
            $contact->getContent(),
            'text/html'
        );
        $message
            ->setFrom($contact->getEmail())
            ->setTo($this->recipient)
            ->setBody($contact->getContent());
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
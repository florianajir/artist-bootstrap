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
use Ajir\ContactBundle\Service\Mailer;
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
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Process form
     *
     * @param Request $request
     *
     * @return bool
     *
     * @throws EmailFailureException
     * @throws EmailNotSentException
     */
    public function process(Request $request)
    {
        if ('POST' === $request->getMethod()) {
            $this->form->handleRequest($request);
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
        $this->mailer->send($contact->getSubject(), $contact->getContent(), $contact->getEmail());
    }
}
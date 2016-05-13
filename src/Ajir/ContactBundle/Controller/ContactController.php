<?php
/**
 * This file is part of the artist-bootstrap project.
 *
 * (c) Florian Ajir <http://github.com/florianajir/artist-bootstrap>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajir\ContactBundle\Controller;

use Ajir\ContactBundle\Exception\EmailSubmitException;
use Ajir\ContactBundle\Form\ContactHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Florian Ajir <florianajir@gmail.com>
 */
class ContactController extends Controller
{
    /**
     * @var ContactHandler
     */
    private $handler;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param ContactHandler $handler
     * @param Session $session
     * @param TranslatorInterface $translator
     */
    public function __construct(ContactHandler $handler, Session $session, TranslatorInterface $translator)
    {
        $this->handler = $handler;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * Contact form action
     */
    public function indexAction(Request $request)
    {
        $form = $this->handler->getForm();
        try {
            $process = $this->handler->process($request);
            $hasError = $form->isSubmitted();
            if ($process) {
                $hasError = false;
                $this->session->getFlashBag()->add('notice', $this->translator->trans('ajir_contact.form.success'));
            }
        } catch (EmailSubmitException $exception) {
            $hasError = true;
        }

        return $this->render('AjirContactBundle:Contact:index.html.twig',
            array(
                'form' => $form->createView(),
                'hasError' => $hasError
            ));
    }
}

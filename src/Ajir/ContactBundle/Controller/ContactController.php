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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Florian Ajir <florianajir@gmail.com>
 */
class ContactController extends Controller
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ContactHandler
     */
    private $handler;

    /**
     * @param EngineInterface $templating
     * @param Session $session
     * @param TranslatorInterface $translator
     * @param ContactHandler $handler
     */
    public function __construct(
        EngineInterface $templating,
        Session $session,
        TranslatorInterface $translator,
        ContactHandler $handler
    ) {
        $this->templating = $templating;
        $this->session = $session;
        $this->translator = $translator;
        $this->handler = $handler;
    }

    /**
     * Contact form action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->templating->renderResponse(
            'AjirContactBundle:Contact:contact_form.html.twig',
            array(
                'form' => $form->createView(),
                'hasError' => $hasError
            )
        );
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: FU923DGR
 * Date: 21/02/2018
 * Time: 15:18
 */

namespace AppBundle\Services;

use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerHTMLService implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var Twig_Environment
     */
    protected $twigTemplate;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param EngineInterface $templating
     * @param \Twig_Environment $twigTemplate
     * @param array $parameters
     */
    public function __construct($mailer, UrlGeneratorInterface $router, EngineInterface $templating, \Twig_Environment $twigTemplate, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->twigTemplate = $twigTemplate;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $message = \Swift_Message::newInstance();
        $banner = $message->embed(\Swift_Image::fromPath($this->parameters['kernel.root_dir'].'/../web/bundles/app/assets/img/edtpe-mail-banner.png'));

        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url,
            'banner' => $banner
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], (string) $user->getEmail(), $message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $message = \Swift_Message::newInstance();
        $banner = $message->embed(\Swift_Image::fromPath($this->parameters['kernel.root_dir'].'/../web/bundles/app/assets/img/edtpe-mail-banner.png'));

        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $resetRequestUrl = $this->router->generate('fos_user_login', [], UrlGeneratorInterface::ABSOLUTE_URL)."#pwd-forgotten";
        $twigParameters = array(
            'user' => $user,
            'confirmationUrl' => $url,
            'resetRequestUrl' =>$resetRequestUrl,
            'banner' => $banner
        );
        $mainTemplate = $this->twigTemplate->loadTemplate($template);
        $subject = $mainTemplate->renderBlock('subject', $twigParameters);
        $body = $mainTemplate->renderBlock('body_html', $twigParameters);

        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url,
            'resetRequestUrl' =>$resetRequestUrl,
            'banner' => $banner
        ));

        $this->sendEmailMessage($subject, $body, $this->parameters['from_email']['resetting'], (string) $user->getEmail(), $message);
    }

    /**
     * @param string $renderedTemplate
     * @param array|string $fromEmail
     * @param array|string $toEmail
     * @param $message
     */
    protected function sendEmailMessage($subject, $body, $fromEmail, $toEmail, $message = null)
    {
        // Render the email, use the first line as the subject, and the rest as the body
//        $renderedLines = explode("\n", trim($renderedTemplate));
//        $subject = array_shift($renderedLines);
//        $body = implode("\n", $renderedLines);

        if(!$message){
            $message = (new \Swift_Message());
        }
        $message
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}
<?php


namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAccountVerifyEmailModel
{
    protected $user_account_id;
    protected $email;
    protected $access_key;
    protected $created_at;
    protected $verified_at;
    protected $verified_from_ip;


    public function getUserAccountId()
    {
        return $this->user_account_id;
    }

    public function setUserAccountId($user_account_id)
    {
        $this->user_account_id = $user_account_id;
    }

    public function getAccessKey()
    {
        return $this->access_key;
    }

    public function setAccessKey($access_key)
    {
        $this->access_key = $access_key;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getVerifiedAt()
    {
        return $this->verified_at;
    }

    public function setVerifiedAt($verified_at)
    {
        $this->verified_at = $verified_at;
        return $this;
    }

    
    public function setFromDataBaseRow($data)
    {
        $this->user_account_id = $data['user_account_id'];
        $this->access_key = $data['access_key'];
        $this->email = $data['email'];
        $utc = new \DateTimeZone("UTC");
        $this->created_at = $data['created_at'] ? new \DateTime($data['created_at'], $utc) : null;
        $this->verified_at = $data['verified_at'] ? new \DateTime($data['verified_at'], $utc) : null;
        $this->verified_from_ip = $data['verified_from_ip'];
    }
    
    public function getIsAlreadyUsed()
    {
        return (boolean)$this->verified_at;
    }
    
    public function sendEmail($app, UserAccountModel $user)
    {
        global $CONFIG;
        
        $message = new \Swift_Message();
        $message->setSubject("Please verify your account on ".$CONFIG->installTitle);
        $message->setFrom(array($CONFIG->emailFrom => $CONFIG->emailFromName));
        $message->setTo($user->getEmail());

        configureAppForThemeVariables();

        $messageText = $app['twig']->render('email/userVerifyEmail.txt.twig', array(
            'user'=>$user,
            'code'=>$this->access_key
        ));
        if ($CONFIG->isDebug) {
            file_put_contents('/tmp/userVerifyEmail.txt', $messageText);
        }
        $message->setBody($messageText);

        $messageHTML = $app['twig']->render('email/userVerifyEmail.html.twig', array(
            'user'=>$user,
            'code'=>$this->access_key
        ));
        if ($CONFIG->isDebug) {
            file_put_contents('/tmp/userVerifyEmail.html', $messageHTML);
        }
        $message->addPart($messageHTML, 'text/html');

        if ($CONFIG->actuallySendEmail) {
            $app['mailer']->send($message);
        }
    }

    /**
     * @return String|null the IP address
     */
    public function getVerifiedFromIp()
    {
        return $this->verified_from_ip;
    }
}

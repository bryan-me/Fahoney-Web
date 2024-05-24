<?php
class PHP_Email_Form {
  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $smtp = array();
  private $messages = array();

  public function add_message($content, $label, $priority = 0) {
    $this->messages[] = array(
      'content' => $content,
      'label' => $label,
      'priority' => $priority
    );
  }

  public function send() {
    $message_body = "";
    foreach ($this->messages as $message) {
      $message_body .= $message['label'] . ": " . $message['content'] . "\n";
    }

    if (!empty($this->smtp)) {
      return $this->send_smtp($message_body);
    } else {
      return $this->send_mail($message_body);
    }
  }

  private function send_mail($message_body) {
    $headers = "From: " . $this->from_name . " <" . $this->from_email . ">";
    return mail($this->to, $this->subject, $message_body, $headers);
  }

  private function send_smtp($message_body) {
    // This is a simplified version; for a full implementation, use PHPMailer
    $transport = (new Swift_SmtpTransport($this->smtp['host'], $this->smtp['port']))
      ->setUsername($this->smtp['username'])
      ->setPassword($this->smtp['password']);

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message($this->subject))
      ->setFrom([$this->from_email => $this->from_name])
      ->setTo([$this->to])
      ->setBody($message_body);

    return $mailer->send($message);
  }
}
?>


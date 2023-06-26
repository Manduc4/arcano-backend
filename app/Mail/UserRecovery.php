<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRecovery extends Mailable
{
  use Queueable, SerializesModels;

  public $user;

  public function __construct($user)
  {
      $this->user = $user;
  }

  public function build()
  {
      return $this->html($this->user);
  }
}

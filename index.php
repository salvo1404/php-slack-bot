<?php
/**
 * Created by IntelliJ IDEA.
 * User: balzanos
 * Date: 5/19/16
 * Time: 2:55 PM
 */

require 'vendor/autoload.php';
use PhpSlackBot\Bot;

class Ciao extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('ciao');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'Ciao bello !' );
	}

}

class Hi extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('hi');
	}

	protected function execute($message, $context) {
		$this->send(
			$this->getCurrentChannel(),
			null,
			"Hi " . $this->getUserNameFromUserId( $message['user'] ) . ".\nWhat can I do for you?" );
	}

}

class MessageInfo extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('info');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, json_encode( $message ) );
	}

}

class Hey extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('hey');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "hey what's up buddy" );
	}

}

class Who extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('who');
	}

	protected function execute($message, $context) {
		$question = strtolower( $message['text'] );

		if( strpos( $question, 'broke' ) !== false || strpos( $question, 'broken' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, "It would be Eugene\nThis guy https://avatars0.githubusercontent.com/u/10735132?v=3&s=400" );
		} elseif ( strpos( $question, 'who am i' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, $this->getUserNameFromUserId( $this->getCurrentUser() ) );
		} elseif ( strpos( $question, 'who are you' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, "My name is J.a.r.v.i.s\nI'm a slack bot created by SPP core team" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Don't know man" );
		}
	}

}

class Thanks extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('thanks');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "Not a problem" );
	}
}

class ThankYou extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('thank');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "Anytime" );
	}
}

class What extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('what');
	}

	protected function execute($message, $context) {
		$question = strtolower( $message['text'] );

		if( strpos( $question, 'what do you want' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, 'I want to sleep' );
		} elseif ( strpos( $question, 'what are you doing' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, "Just chilling in the cloud" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Not sure about that" );
		}
	}

}

class How extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('how');
	}

	protected function execute($message, $context) {
		$question = strtolower( $message['text'] );

		if( strpos( $question, 'how are you' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, "Had a big one last night.\nBit hungover.\nHow are you?" );
		} elseif ( strpos( $question, 'how is going' ) !== false ) {
			$this->send($this->getCurrentChannel(), null, "Not too bad man" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Hey buddy give me a break" );
		}
	}

}

class Help extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('help');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $message['text'] );
	}

}

class SumoTail extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('tail');
	}

	protected function execute($message, $context) {
		$command = explode(" ", $message['text'] );
		/**
		 * e.g. tail sit-heraldsun start
		 *
		 * 0 tail
		 * 1 env
		 * 2 action (start, stop)
		 *
		 */
		if ( count( $command ) < 3 ) {
			$this->send($this->getCurrentChannel(), null, "Error: i.e 'tail sit-heraldsun start'" );
		} else {
			$channel   = $message['channel'];
			$user      = $message['user'];
			$collector = 'spp-' . strtolower( $command[1] );
			$action    = strtolower( $command[2] );

			if ( 'start' === $action ) {
				$exec = "curl 'http://localhost:8080/?slack_id=" . $channel . "&source=" . $collector . "'";
//			    exec( $exec );
				$this->send($this->getCurrentChannel(), null, '>' . $message['text'] );
				$this->send($this->getCurrentChannel(), null, '```' . $exec . '```' );
			} elseif ( 'stop' === $action ) {
				$exec = "curl 'STOP'"; // Add command to stop
//			    exec( $exec );
				$this->send($this->getCurrentChannel(), null, '>' . $message['text'] );
				$this->send($this->getCurrentChannel(), null, '```' . $exec . '```' );
			} else {
				$this->send($this->getCurrentChannel(), null, 'Error: Action non allowed (start|stop)' );
			}
		}
	}
}

class SumoWebhook extends \PhpSlackBot\Webhook\BaseWebhook {

	protected function configure() {
		$this->setName('sumo');
	}

	protected function execute($payload, $context) {
		$this->send( $payload['channel'], null, '```' . $payload['text'] . '```' );
	}
}


if ( file_exists( __DIR__ . '/config.php' ) ) {
	$config = include( __DIR__ . '/config.php' );
}

$bot = new Bot();
$bot->setToken( $config['token'] ); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCommand( new Ciao() );
$bot->loadCommand( new Hi() );
$bot->loadCommand( new MessageInfo() );
$bot->loadCommand( new Who() );
$bot->loadCommand( new How() );
$bot->loadCommand( new What() );
$bot->loadCommand( new Hey() );
$bot->loadCommand( new Thanks() );
$bot->loadCommand( new ThankYou() );
$bot->loadCommand( new SumoTail() );
$bot->loadWebhook( new SumoWebhook() );
$bot->enableWebserver( 8080, $config['webhook_key'] );
// $bot->loadCatchAllCommand( new SumoLiveTail() );
$bot->run();
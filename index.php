<?php
/**
 * Created by IntelliJ IDEA.
 * User: balzanos
 * Date: 5/19/16
 * Time: 2:55 PM
 */

require 'vendor/autoload.php';
use PhpSlackBot\Bot;

// Custom command
class MyCommand extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('ciao');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'Ciao bello !' );
	}

}

// Custom command
class Eugenesfault extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('who-broke-the-site');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'Eugene');
	}

}

// Custom command
class Context extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('context');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'message = ' . json_encode( $message ) . ', channel = ' . $context['channel'] );
	}

}

// Custom command
class MessageInfo extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('info');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, json_encode( $message ) );
	}

}

// Custom command
class Hey extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('hey');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "hey what's up buddy" );
	}

}

// Custom command
class Who extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('who');
	}

	protected function execute($message, $context) {
		$question = strtoupper( $message['text'] );

		if( $question  === 'WHO AM I') {
			$this->send($this->getCurrentChannel(), null, $this->getUserNameFromUserId( $this->getCurrentUser() ) );
		} elseif ( $question === 'WHO BROKE THE SITE' ) {
			$this->send($this->getCurrentChannel(), null, "Always Eugene\nThis guy https://avatars0.githubusercontent.com/u/10735132?v=3&s=400" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Don't know man" );
		}
	}

}

// Custom command
class Thanks extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('thanks');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "Not a problem" );
	}
}

// Custom command
class ThankYou extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('thank');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "Anytime" );
	}
}

// Custom command
class What extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('what');
	}

	protected function execute($message, $context) {
		$question = strtoupper( $message['text'] );

		if( $question  === 'WHAT DO YOU WANT') {
			$this->send($this->getCurrentChannel(), null, 'I want to sleep' );
		} elseif ( $question === "WHAT'S UP" ) {
			$this->send($this->getCurrentChannel(), null, "Hey buddy, let's go for a beer" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Too many questions..Sorry I'm hungover" );
		}
	}

}

// Custom command
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
			$channel = $message['channel'];
			$user = $message['user'];
			$collector = 'spp-' . $command[1];
			$exec = "curl 'http://localhost:8080/?slack_id=" . $channel . "&source=" . $collector . "'";
//			exec( $exec );
			$this->send($this->getCurrentChannel(), null, '>' . $message['text'] );
			$this->send($this->getCurrentChannel(), null, '```' . $exec . '```' );
		}
	}
}

// Custom command
class Params extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('params');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $message['text'] );
	}

}

// Custom command
class Help extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('help');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $message['text'] );
	}

}

class SumoWebhook extends \PhpSlackBot\Webhook\BaseWebhook {

	protected function configure() {
		$this->setName('sumo');
	}

	protected function execute($payload, $context) {
		// $payload['channel'] = $this->getChannelIdFromChannelName($payload['channel']);

		$this->send( $payload['channel'], null, $payload['text'] );
		// $this->getClient()->send(json_encode($payload));
	}

}


if ( file_exists( __DIR__ . '/config.php' ) ) {
	$config = include( __DIR__ . '/config.php' );
}

$bot = new Bot();
$bot->setToken( $config['token'] ); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCommand( new MyCommand() );
$bot->loadCommand( new Eugenesfault() );
$bot->loadCommand( new Context() );
$bot->loadCommand( new MessageInfo() );
$bot->loadCommand( new Who() );
$bot->loadCommand( new What() );
$bot->loadCommand( new Hey() );
$bot->loadCommand( new Params() );
$bot->loadCommand( new SumoTail() );
$bot->loadWebhook( new SumoWebhook() );
$bot->enableWebserver( 8080, $config['webhook_key'] );
// $bot->loadCatchAllCommand( new SumoLiveTail() );
$bot->run();
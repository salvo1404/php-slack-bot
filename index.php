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

/**
 * Command to list available sites
 *
 * e.g. list [<environment>]
 *
 * 0 list
 * 1 [<environment>]
 */
class SiteList extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('list');
	}

	protected function execute($message, $context) {
		$params = explode(" ", $message['text'] );
		$helper = new Helper();

		if ( count( $params ) === 1 ) {
			$this->send(
				$this->getCurrentChannel(),
				null,
				">*List of available instances*: \n" . implode( "\n", $helper->get_sites_list() )
			);
		} elseif ( count( $params ) === 2 ) {
			$env = $params[1];
			$this->send(
				$this->getCurrentChannel(),
				null,
				">*List of available " . strtoupper( $env ) . " instances*: \n" . implode( "\n", $helper->get_env_list( $env ) )
			);
		} else {
			$this->send($this->getCurrentChannel(), null, $helper->usage() );
		}
	}

}

class Helper extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('help');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $this->usage() );
	}

	public function usage() {
		$tailUsage = "*Usage*: `tail <instance> < start | stop >`" . "\n" .
		             "     Tail Heraldsun SIT error log: " . "`tail sit-heraldsun start`" . "\n" .
		             "     Tail Perthnow UAT error log: " . "`tail uat-perthnow start`" . "\n" .
		             "     Tail TheAustralian PROD error log: " . "`tail prod-theaustralian start`" . "\n" .
		             "     Stop tailing TheAustralian PROD error log: " . "`tail prod-theaustralian stop`";

		$listUsage = "*Usage*: `list`" . "\n" .
		             "     List available instances: " . "`list`" . "\n" .
		             "*Usage*: `list <environment>`" . "\n" .
		             "     List available SIT instances: " . "`list sit`" . "\n" .
		             "     List available PROD instances: " . "`list prod`" . "\n";

		return $tailUsage . "\n" . $listUsage;
	}

	public function get_sites_list() {
		$sites = array();
		$list  = array();
		if ( file_exists( __DIR__ . '/instances.php' ) ) {
			$sites = include( __DIR__ . '/instances.php' );
		}

		foreach( $sites as $site ) {
			$list[] = 'sit-' . $site;
			$list[] = 'uat-' . $site;
			$list[] = 'prod-' . $site;
			$list[] = 'ls-' . $site;
		}

		return $list;
	}

	public function get_env_list( $env ) {
		$list = array();
		foreach( $this->get_sites_list() as $site ) {
			if ( 0 === strpos( $site, $env ) ) {
				$list[] = $site;
			}
		}

		return $list;
	}
}

/**
 * Command to tail sumolog
 *
 * e.g. tail sit-heraldsun start
 *
 * 0 tail
 * 1 <instance>
 * 2 <start|stop>
 */
class SumoTail extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('tail');
	}

	protected function execute($message, $context) {
		$params = explode(" ", $message['text'] );
		$helper = new Helper();

		if ( count( $params ) !== 3 ) {
			$this->send($this->getCurrentChannel(), null, $helper->usage() );
		} elseif ( ! in_array( $params[1], $helper->get_sites_list(), true ) ) {
			$this->send($this->getCurrentChannel(), null, '*Error*: Instance does not exist' );
		} else {
			$command   = $message['text'];
			$channel   = $message['channel'];
			$user      = $message['user'];
			$collector = 'spp-' . strtolower( $params[1] );
			$action    = strtolower( $params[2] );

			if ( 'start' === $action ) {
				$exec = "curl 'http://localhost:8088/?slack_id=" . $channel . "&source=" . $collector . "&action=" . $action . "'";
			    exec( $exec );
				$this->send($this->getCurrentChannel(), null, '> *' . $command . '*' );
				$this->send($this->getCurrentChannel(), null, '```' . $exec . '```' );
			} elseif ( 'stop' === $action ) {
				$exec = "curl 'http://localhost:8088/?slack_id=" . $channel . "&source=" . $collector . "&action=" . $action . "'";
			    exec( $exec );
				$this->send($this->getCurrentChannel(), null, '>*' . $command . '*' );
				$this->send($this->getCurrentChannel(), null, '```' . $exec . '```' );
			} else {
				$this->send($this->getCurrentChannel(), null, '*Error*: Action non allowed (start|stop)' );
				$this->send($this->getCurrentChannel(), null, $helper->usage() );
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

class PrivateMessage extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('private');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, $this->getImIdFromUserId( $message['user'] ) );
	}

}

if ( file_exists( __DIR__ . '/config.php' ) ) {
	$config = include( __DIR__ . '/config.php' );
}

$bot = new Bot();
$bot->setToken( $config['token'] ); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCommand( new PrivateMessage() );
$bot->loadCommand( new Ciao() );
$bot->loadCommand( new Hi() );
$bot->loadCommand( new Helper() );
$bot->loadCommand( new SiteList() );
$bot->loadCommand( new MessageInfo() );
$bot->loadCommand( new Who() );
$bot->loadCommand( new How() );
$bot->loadCommand( new What() );
$bot->loadCommand( new Hey() );
$bot->loadCommand( new Thanks() );
$bot->loadCommand( new ThankYou() );
$bot->loadCommand( new SumoTail() );
$bot->loadWebhook( new SumoWebhook() );
$bot->enableWebserver( 8080, $config['webhook_auth'] );
$bot->run();
<?php
/**
 * ownCloud
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License,
 * as published by the Free Software Foundation;
 * either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\RawMinkContext;
use Page\NotesPage;

require_once 'bootstrap.php';

/**
 * Notes context.
 */
class NotesContext extends RawMinkContext implements Context {
	private $notesPage;
	private $webUIGeneralContext;

	/**
	 * NotesContext constructor.
	 *
	 * @param NotesPage $notesPage
	 */
	public function __construct(NotesPage $notesPage) {
		$this->notesPage = $notesPage;
	}

	/**
	 * @Given the user has browsed to the notes app
	 * @When the user browses to the notes app using the webUI
	 *
	 * @return void
	 */
	public function theUserBrowsesToTheNotesApp():void {
		$this->notesPage->open();
		$this->notesPage->waitTillPageIsLoaded($this->getSession());
	}

	/**
	 * @Then the notes empty-state placeholder should be displayed on the webUI
	 *
	 * @return void
	 */
	public function theNotesEmptyStatePlaceholderShouldBeDisplayed():void {
		PHPUnit\Framework\Assert::assertTrue(
			$this->notesPage->isEmptyStateVisible(),
			"the notes empty-state placeholder is not displayed but should be"
		);
	}

	/**
	 * @Then the notes empty-state heading should be :expected
	 *
	 * @param string $expected
	 *
	 * @return void
	 */
	public function theNotesEmptyStateHeadingShouldBe(string $expected):void {
		PHPUnit\Framework\Assert::assertEquals(
			$expected,
			$this->notesPage->getEmptyStateHeading()
		);
	}

	/**
	 * This will run before EVERY scenario.
	 * It will set the properties for this object.
	 *
	 * @BeforeScenario
	 *
	 * @param BeforeScenarioScope $scope
	 *
	 * @return void
	 */
	public function before(BeforeScenarioScope $scope):void {
		// Get the environment
		$environment = $scope->getEnvironment();
		// Get all the contexts you need in this context
		$this->webUIGeneralContext = $environment->getContext('WebUIGeneralContext');
	}
}

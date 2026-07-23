<?php declare(strict_types=1);
/**
 * ownCloud
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Page;

use Behat\Mink\Session;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

/**
 * Notes page.
 */
class NotesPage extends OwncloudPage {
	/**
	 * @var string $path the path to the notes app, relative to the base url
	 */
	protected $path = '/index.php/apps/notes/';

	protected $emptyContentXpath = "//div[@id='app-content']//div[contains(@class,'emptycontent')]";
	protected $emptyContentHeadingXpath = "//div[@id='app-content']//div[contains(@class,'emptycontent')]//h2";
	protected $addNoteButtonId = "note-add";

	/**
	 * wait till the notes app is fully loaded
	 *
	 * @param Session $session
	 * @param int $timeout_msec
	 *
	 * @return void
	 */
	public function waitTillPageIsLoaded(
		Session $session,
		int $timeout_msec = STANDARD_UI_WAIT_TIMEOUT_MILLISEC
	): void {
		$this->waitTillElementIsNotNull($this->addNoteButtonXpath(), $timeout_msec);
		$this->waitForOutstandingAjaxCalls($session);
	}

	/**
	 * @return string
	 */
	private function addNoteButtonXpath(): string {
		return "//*[@id='" . $this->addNoteButtonId . "']";
	}

	/**
	 * is the empty-state placeholder currently displayed
	 *
	 * @return bool
	 */
	public function isEmptyStateVisible(): bool {
		$emptyContent = $this->find("xpath", $this->emptyContentXpath);
		return $emptyContent !== null && $emptyContent->isVisible();
	}

	/**
	 * get the heading text of the empty-state placeholder
	 *
	 * @return string
	 * @throws ElementNotFoundException
	 */
	public function getEmptyStateHeading(): string {
		$heading = $this->find("xpath", $this->emptyContentHeadingXpath);
		if ($heading === null) {
			throw new ElementNotFoundException(
				"could not find the empty-state heading"
			);
		}
		return $heading->getText();
	}

	/**
	 * click the "New note" button in the sidebar
	 *
	 * @param Session $session
	 *
	 * @return void
	 * @throws ElementNotFoundException
	 */
	public function createNote(Session $session): void {
		$addButton = $this->findById($this->addNoteButtonId);
		if ($addButton === null) {
			throw new ElementNotFoundException(
				"could not find the new note button"
			);
		}
		$addButton->click();
		$this->waitForOutstandingAjaxCalls($session);
	}
}

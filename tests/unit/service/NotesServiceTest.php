<?php
/**
 * ownCloud - Notes
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2012, 2014
 */

namespace OCA\Notes\Service;

use OCP\IConfig;
use PHPUnit\Framework\TestCase;
use OCP\Files\IRootFolder;
use OCP\Files\Folder;
use OCP\IL10N;

class NotesServiceTest extends TestCase {
	private $root;
	private $service;
	private $userId;
	private $l10n;
	private $userFolder;
	private $userHomeFolder;

	public function setUp(): void {
		$this->root = $this->getMockBuilder(IRootFolder::class)
			->getMock();
		$this->userFolder = $this->getMockBuilder(Folder::class)
			->getMock();
		$this->userHomeFolder = $this->getMockBuilder(Folder::class)
			->getMock();
		$this->l10n = $this->getMockBuilder(IL10N::class)
			->getMock();
		$this->userId = 'john';
		$config = $this->createMock(IConfig::class);
		$config->method('getUserValue')->willReturn('Notes');

		$this->service = new NotesService($this->root, $this->l10n, $config);
	}

	private function createNode($name, $type, $mime, $mtime=0, $content='', $id=0, $path='/') {
		if ($type === 'folder') {
			$iface = 'OCP\Files\Folder';
		} else {
			$iface = 'OCP\Files\File';
		}
		$node = $this->getMockBuilder($iface)
			->getMock();
		$node
			->method('getType')
			->willReturn($type);
		$node
			->method('getMimeType')
			->willReturn($mime);
		$node
			->method('getName')
			->willReturn($name);
		$node
			->method('getMTime')
			->willReturn($mtime);
		$node->expects($this->any())
			->method('getId')
			->willReturn($id);
		$node
			->method('getPath')
			->willReturn($path);
		if ($type === 'file') {
			$node
				->method('getContent')
				->willReturn($content);
		}
		return $node;
	}

	private function expectUserFolder(): void {
		$this->root
			->method('getUserFolder')
			->willReturn($this->userHomeFolder);

		$this->userHomeFolder->expects($this->once())
			->method('nodeExists')
			->with($this->equalTo('Notes'))
			->willReturn(true);
		$this->userHomeFolder
			->method('get')
			->with($this->equalTo('Notes'))
			->willReturn($this->userFolder);

		$this->userFolder->method('getType')->willReturn('dir');
		$this->userFolder->method('isShared')->willReturn(false);
	}

	public function testGetAll() {
		$nodes = [];
		$nodes[] = $this->createNode('file1.txt', 'file', 'text/plain');
		$nodes[] = $this->createNode('file1.jpg', 'file', 'image/jpeg');
		$nodes[] = $this->createNode('file3.txt', 'folder', 'text/plain');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getDirectoryListing')
			->will($this->returnValue($nodes));

		$result = $this->service->getAll($this->userId);

		$this->assertEquals('file1', $result[0]->getTitle());
		$this->assertCount(1, $result);
	}

	public function testGet() {
		$nodes = [];
		$nodes[] = $this->createNode('file1.txt', 'file', 'text/plain');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->will($this->returnValue($nodes));
		$result = $this->service->get(2, $this->userId);

		$this->assertEquals('file1', $result->getTitle());
	}

	/**
	 */
	public function testGetDoesNotExist() {
		$this->expectException(\OCA\Notes\Service\NoteDoesNotExistException::class);

		$nodes = [];

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->willReturn($nodes);
		$this->service->get(2, $this->userId);
	}

	/**
	 */
	public function testGetDoesNotExistWrongExtension() {
		$this->expectException(\OCA\Notes\Service\NoteDoesNotExistException::class);

		$nodes = [];
		$nodes[] = $this->createNode('file1.jpg', 'file', 'image/jpeg');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->will($this->returnValue($nodes));

		$this->service->get(2, $this->userId);
	}

	public function testDelete() {
		$nodes = [];
		$nodes[] = $this->createNode('file1.txt', 'file', 'text/plain');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->will($this->returnValue($nodes));
		$nodes[0]->expects($this->once())
			->method('delete');

		$this->service->delete(2, $this->userId);
	}

	/**
	 */
	public function testDeleteDoesNotExist() {
		$this->expectException(\OCA\Notes\Service\NoteDoesNotExistException::class);

		$nodes = [];

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->will($this->returnValue($nodes));
		$this->service->delete(2, $this->userId);
	}

	/**
	 */
	public function testDeleteDoesNotExistWrongExtension() {
		$this->expectException(\OCA\Notes\Service\NoteDoesNotExistException::class);

		$nodes = [];
		$nodes[] = $this->createNode('file1.jpg', 'file', 'image/jpeg');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(2))
			->will($this->returnValue($nodes));

		$this->service->delete(2, $this->userId);
	}

	private function expectGenerateFileName($at=0, $title, $id=0, $branch=0) {
		if ($branch === 0) {
			$this->userFolder->expects($this->once())
				->method('nodeExists')
				->with($this->equalTo($title . '.txt'))
				->willReturn(false);
		} elseif ($branch === 1) {
			$this->userFolder->expects($this->once())
				->method('nodeExists')
				->with($this->equalTo($title . '.txt'))
				->willReturn(true);
			$file = $this->createNode('file1.txt', 'file', 'text/plain', 0, '', 0);
			$this->userFolder->expects($this->once())
				->method('get')
				->with($this->equalTo($title . '.txt'))
				->willReturn($file);
		} elseif ($branch === 2) {
			$this->userFolder
				->expects($this->exactly(3))
				->method('nodeExists')
				->withConsecutive(
					[$this->equalTo($title . '.txt')],
					[$this->equalTo($title . ' (2).txt')],
					[$this->equalTo($title . ' (3).txt')],
				)
				->willReturnOnConsecutiveCalls(
					true,
					true,
					false,
				);

			$file = $this->createNode('file1.txt', 'file', 'text/plain', 0, '', 0);
			$this->userFolder
				->expects($this->exactly(2))
				->method('get')
				->withConsecutive(
					[$this->equalTo($title . '.txt')],
					[$this->equalTo($title . ' (2).txt')],
				)
				->willReturnOnConsecutiveCalls(
					$file,
					$file,
				);
		}
	}

	public function testCreate() {
		$this->l10n->expects($this->once())
			->method('t')
			->with($this->equalTo('New note'))
			->willReturn('New note');
		$this->expectUserFolder();

		$this->expectGenerateFileName(0, 'New note');

		$file = $this->createNode('file1.txt', 'file', 'text/plain');
		$this->userFolder->expects($this->once())
			->method('newFile')
			->with($this->equalTo('New note.txt'))
			->willReturn($file);

		$this->userFolder->expects($this->once())
			->method('nodeExists')
			->with($this->equalTo('New note.txt'))
			->willReturn(false);

		$note = $this->service->create($this->userId);

		$this->assertEquals('file1', $note->getTitle());
	}

	public function testCreateExists() {
		$this->l10n->expects($this->once())
			->method('t')
			->with($this->equalTo('New note'))
			->willReturn('New note');
		$this->expectUserFolder();

		$this->expectGenerateFileName(0, 'New note', 0, 2);

		$file = $this->createNode('file1.txt', 'file', 'text/plain');
		$this->userFolder->expects($this->once())
			->method('newFile')
			->with($this->equalTo('New note (3).txt'))
			->willReturn($file);

		$note = $this->service->create($this->userId);

		$this->assertEquals('file1', $note->getTitle());
	}

	public function testUpdate() {
		$nodes = [];
		$nodes[] = $this->createNode('file1.txt', 'file', 'text/plain');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(3))
			->willReturn($nodes);

		$this->l10n->expects($this->once())
			->method('t')
			->with($this->equalTo('New note'))
			->willReturn('New note');
		$this->expectUserFolder();

		$this->expectGenerateFileName(1, 'New note', 0, 2);

		$path = '/' . $this->userId . '/files/Notes/New note (3).txt';
		$nodes[0]->expects($this->once())
			->method('move')
			->with($this->equalTo($path));

		$note = $this->service->update(3, '', $this->userId);

		$this->assertEquals('file1', $note->getTitle());
	}

	public function testUpdateWithContent() {
		$nodes = [];
		$nodes[] = $this->createNode('file1.txt', 'file', 'text/plain');

		$this->expectUserFolder();
		$this->userFolder->expects($this->once())
			->method('getById')
			->with($this->equalTo(3))
			->willReturn($nodes);

		$this->l10n->expects($this->never())
			->method('t');
		$this->expectUserFolder();

		$this->expectGenerateFileName(1, 'some', 0, 2);

		$path = '/' . $this->userId . '/files/Notes/some (3).txt';
		$nodes[0]->expects($this->once())
			->method('move')
			->with($this->equalTo($path));

		$note = $this->service->update(3, "some\nnice", $this->userId);

		$this->assertEquals('file1', $note->getTitle());
	}
}

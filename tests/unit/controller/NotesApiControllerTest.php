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

namespace OCA\Notes\Controller;

use PHPUnit\Framework\TestCase;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http;

use OCA\Notes\Service\NoteDoesNotExistException;
use OCA\Notes\Db\Note;
use OCP\IRequest;
use OCA\Notes\Service\NotesService;
use OCP\IUser;

class NotesApiControllerTest extends TestCase {
	private $request;
	private $service;
	private $userId;
	private $appName;
	private $controller;

	public function setUp(): void {
		$this->request = $this->getMockBuilder(IRequest::class)
			->disableOriginalConstructor()
			->getMock();
		$this->service = $this->getMockBuilder(NotesService::class)
			->disableOriginalConstructor()
			->getMock();
		$this->userId = 'john';
		$this->appName = 'notes';
		$user = $this->getMockBuilder(IUser::class)
			->disableOriginalConstructor()
			->getMock();
		$user->method('getUID')->willReturn($this->userId);
		$userSession = $this->getMockBuilder('OCP\IUserSession')
			->disableOriginalConstructor()
			->getMock();
		$userSession->method('getUser')->willReturn($user);
		$this->controller = new NotesApiController(
			$this->appName,
			$this->request,
			$this->service,
			$userSession
		);
	}

	/**
	 * GET /notes/
	 */
	public function testGetAll() {
		$expected = [new Note, new Note];

		$this->service->expects($this->once())
			->method('getAll')
			->with($this->equalTo($this->userId))
			->willReturn($expected);

		$response = $this->controller->index();

		$this->assertEquals($expected, $response->getData());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	public function testGetAllHide() {
		$note1 = Note::fromRow([
			'id' => 3,
			'modified' => 123,
			'title' => 'test',
			'content' => 'yo'
		]);
		$note2 = Note::fromRow([
			'id' => 4,
			'modified' => 111,
			'title' => 'abc',
			'content' => 'deee'
		]);
		$notes = [
			$note1, $note2
		];

		$this->service->expects($this->once())
			->method('getAll')
			->with($this->equalTo($this->userId))
			->willReturn($notes);

		$response = $this->controller->index('title,content');

		$this->assertEquals(\json_encode([
			[
				'id' => 3,
				'modified' => 123,
				'favorite' => false,
			],
			[
				'id' => 4,
				'modified' => 111,
				'favorite' => false,
			]
		]), \json_encode($response->getData()));
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	/**
	 * GET /notes/1
	 */
	public function testGet() {
		$id = 1;
		$expected = new Note;

		$this->service->expects($this->once())
			->method('get')
			->with(
				$this->equalTo($id),
				$this->equalTo($this->userId)
			)
			->willReturn($expected);

		$response = $this->controller->get($id);

		$this->assertEquals($expected, $response->getData());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	public function testGetHide() {
		$note = Note::fromRow([
			'id' => 3,
			'modified' => 123,
			'title' => 'test',
			'content' => 'yo'
		]);

		$this->service->expects($this->once())
			->method('get')
			->with(
				$this->equalTo(3),
				$this->equalTo($this->userId)
			)
			->willReturn($note);

		$response = $this->controller->get(3, 'title,content');

		$this->assertEquals(\json_encode([
			'id' => 3,
			'modified' => 123,
			'favorite' => false,
		]), \json_encode($response->getData()));
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	public function testGetDoesNotExist() {
		$id = 1;
		$expected = ['hi'];

		$this->service->expects($this->once())
			->method('get')
			->with(
				$this->equalTo($id),
				$this->equalTo($this->userId)
			)
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->controller->get($id);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	/**
	 * POST /notes
	 */
	public function testCreate() {
		$content = 'yii';
		$note = new Note();
		$note->setId(4);

		$this->service->expects($this->once())
			->method('create')
			->with($this->equalTo($this->userId))
			->willReturn($note);

		$this->service->expects($this->once())
			->method('update')
			->with(
				$this->equalTo($note->getId()),
				$this->equalTo($content),
				$this->equalTo($this->userId)
			)
			->willReturn($note);

		$response = $this->controller->create($content);

		$this->assertEquals($note, $response->getData());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	/**
	 * PUT /notes/
	 */
	public function testUpdate() {
		$id = 1;
		$content = 'yo';
		$expected = ['hi'];

		$this->service->expects($this->once())
			->method('update')
			->with(
				$this->equalTo($id),
				$this->equalTo($content),
				$this->equalTo($this->userId)
			)
			->willReturn($expected);

		$response = $this->controller->update($id, $content);

		$this->assertEquals($expected, $response->getData());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	public function testUpdateDoesNotExist() {
		$id = 1;
		$content = 'yo';

		$this->service->expects($this->once())
			->method('update')
			->with(
				$this->equalTo($id),
				$this->equalTo($content),
				$this->equalTo($this->userId)
			)
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->controller->update($id, $content);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertInstanceOf(DataResponse::class, $response);
	}

	/**
	 * DELETE /notes/
	 */
	public function testDelete() {
		$id = 1;

		$this->service->expects($this->once())
			->method('delete')
			->with(
				$this->equalTo(1),
				$this->equalTo($this->userId)
			);

		$response = $this->controller->destroy($id);

		$this->assertInstanceOf(DataResponse::class, $response);
	}

	public function testDeleteDoesNotExist() {
		$id = 1;

		$this->service->expects($this->once())
			->method('delete')
			->with(
				$this->equalTo(1),
				$this->equalTo($this->userId)
			)
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->controller->destroy($id);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertInstanceOf(DataResponse::class, $response);
	}
}

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

use \OCP\IRequest;
use \OCP\AppFramework\Http\JSONResponse;
use \OCP\AppFramework\Http;

use \OCA\Notes\Service\NoteDoesNotExistException;
use \OCA\Notes\Utility\ControllerTestUtility;
use \OCA\Notes\Db\Note;

class NotesApiControllerTestCase extends \OCA\Notes\Tests\Unit\NotesUnitTestCase {


	/**
	 * Gets run before each test
	 */
	public function setUp(){
		parent::setUp();
		$test = &$this;

		$this->container->registerService('NotesService', function ($c) use ($test) {
			return $test->getMockBuilder(
				'\OCA\Notes\Service\NotesService')
				->disableOriginalConstructor()
				->getMock();
		});
	}


	/**
	 * GET /notes/
	 */
	public function testGetAll(){
		$expected = array(
			'hi'
		);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('getAll')
			->will($this->returnValue($expected));

		$response = $this->container->query('NotesApiController')->index();

		$this->assertEquals($expected, $response->getData());
		$this->assertTrue($response instanceof JSONResponse);
	}


	public function testGetAllHide(){
		$note1 = Note::fromFile(array(
			'fileid' => 3,
			'mtime' => 123,
			'name' => 'test',
			'content' => 'yo'
		));
		$note2 = Note::fromFile(array(
			'fileid' => 4,
			'mtime' => 111,
			'name' => 'abc',
			'content' => 'deee'
		));
		$notes = array(
			$note1, $note2
		);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('getAll')
			->will($this->returnValue($notes));

		$response = $this->container->query('NotesApiController')->index('title,content');

		$this->assertEquals(json_encode(array(
			array(
				'modified' => 123,
				'id' => 3,
			),
			array(
				'modified' => 111,
				'id' => 4,
			))
		), json_encode($response->getData()));
		$this->assertTrue($response instanceof JSONResponse);
	}


	/**
	 * GET /notes/1
	 */
	public function testGet(){
		$id = 1;
		$expected = array(
			'hi'
		);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('get')
			->with($this->equalTo($id))
			->will($this->returnValue($expected));

		$response = $this->container->query('NotesApiController')->get($id);

		$this->assertEquals($expected, $response->getData());
		$this->assertTrue($response instanceof JSONResponse);
	}

	public function testGetHide(){
		$note = Note::fromFile(array(
			'fileid' => 3,
			'mtime' => 123,
			'name' => 'test',
			'content' => 'yo'
		));

		$this->container->query('NotesService')
			->expects($this->once())
			->method('get')
			->with($this->equalTo(3))
			->will($this->returnValue($note));

		$response = $this->container->query('NotesApiController')->get(3, 'title,content');

		$this->assertEquals(json_encode(array(
			'modified' => 123,
			'id' => 3,
		)), json_encode($response->getData()));
		$this->assertTrue($response instanceof JSONResponse);
	}


	public function testGetDoesNotExist(){
		$id = 1;
		$expected = array(
			'hi'
		);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('get')
			->with($this->equalTo($id))
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->container->query('NotesApiController')->get($id);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertTrue($response instanceof JSONResponse);
	}


	/**
	 * POST /notes
	 */
	public function testCreate(){
		$content = 'yii';
		$note = new Note();
		$note->setId(4);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('create')
			->will($this->returnValue($note));

		$this->container->query('NotesService')
			->expects($this->once())
			->method('update')
			->with($this->equalTo($note->getId()),
				$this->equalTo($content))
			->will($this->returnValue($note));

		$response = $this->container->query('NotesApiController')->create($content);

		$this->assertEquals($note, $response->getData());
		$this->assertTrue($response instanceof JSONResponse);
	}


	/**
	 * PUT /notes/
	 */
	public function testUpdate(){
		$id = 1;
		$content = 'yo';
		$expected = array(
			'hi'
		);

		$this->container->query('NotesService')
			->expects($this->once())
			->method('update')
			->with($this->equalTo($id),
				$this->equalTo($content))
			->will($this->returnValue($expected));

		$response = $this->container->query('NotesApiController')->update($id, $content);

		$this->assertEquals($expected, $response->getData());
		$this->assertTrue($response instanceof JSONResponse);
	}


	public function testUpdateDoesNotExist(){
		$id = 1;
		$content = 'yo';

		$this->container->query('NotesService')
			->expects($this->once())
			->method('update')
			->with($this->equalTo($id),
				$this->equalTo($content))
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->container->query('NotesApiController')->update($id, $content);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertTrue($response instanceof JSONResponse);
	}


	/**
	 * DELETE /notes/
	 */
	public function testDelete(){
		$id = 1;

		$this->container->query('NotesService')
			->expects($this->once())
			->method('delete');

		$response = $this->container->query('NotesApiController')->destroy($id);

		$this->assertTrue($response instanceof JSONResponse);
	}


	public function testDeleteDoesNotExist(){
		$id = 1;

		$this->container->query('NotesService')
			->expects($this->once())
			->method('delete')
			->will($this->throwException(new NoteDoesNotExistException()));

		$response = $this->container->query('NotesApiController')->destroy($id);

		$this->assertEquals(Http::STATUS_NOT_FOUND, $response->getStatus());
		$this->assertTrue($response instanceof JSONResponse);
	}


}

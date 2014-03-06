<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace PocketMine\Event;

use PocketMine;
use PocketMine\Event\Event as Event;

abstract class EventHandler{

	public static function callEvent(Event $event){
		if(count($event::$handlerPriority) === 0){
			return Event::NORMAL;
		}
		foreach($event::$handlerPriority as $priority => $handlerList){
			if(count($handlerList) > 0){
				$event->setPrioritySlot($priority);
				foreach($handlerList as $handler){
					call_user_func($handler, $event);
				}
				if($event->isForced()){
					if($event instanceof CancellableEvent and $event->isCancelled()){
						return Event::DENY;
					} else{
						return Event::ALLOW;
					}
				}
			}
		}

		if($event instanceof CancellableEvent and $event->isCancelled()){
			return Event::DENY;
		} elseif($event->isAllowed()){
			return Event::ALLOW;
		} else{
			return Event::NORMAL;
		}
	}

}
<?php

declare(strict_types=1);

namespace Zedstar16\NoVoid;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\entity\Entity;
class Main extends PluginBase implements Listener{

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info(TextFormat::GREEN . "NoVoidPlus by Zed enabled!");
        $this->saveResource("config.yml");
    }

     public function onMove(PlayerMoveEvent $event): void{
        if($event->getTo()->getFloorY() < 0){
            $player = $event->getPlayer();

            $config = $this->getConfig();
            $coords = $config->get("teleport-to-default-spawn") ? $this->getServer()->getDefaultLevel()->getSafeSpawn() : new Position((int)$config->get("spawn-x"), (int)$config->get("spawn-y"), (int)$config->get("spawn-z"), $this->getServer()->getLevelByName((string)$config->get("spawn-level"))); // (int) and (string) for PhpStorm reasons
            $player->teleport($coords);
            

            $level = $this->getServer()->getDefaultLevel();
            $spawn = $level->getSafeSpawn();
            $x = $spawn->getFloorX();
            $y = $spawn->getFloorY();
            $z = $spawn->getFloorZ();
            
            if($this->getConfig()->get("teleport-to-default-spawn")){
               $player->teleport(new Position($x, $y, $z, $level));
            }else{
                $player->teleport(new Vector3($this->getConfig()->get("spawn-x"), $this->getConfig()->get("spawn-y"), $this->getConfig()->get("spawn-z"), $this->getConfig()->get("spawn-level")));
            }
            $player->sendMessage($config->get("message"));

        }
    }

    public function onDisable() : void{
        $this->getLogger()->info("NoVoidPlus disabled");
    }
}

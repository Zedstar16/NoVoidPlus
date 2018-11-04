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
        $this->saveResource("config.yml");
        $config = $this->getConfig();

        if(!is_bool($config->get("teleport-to-default-spawn"))){
           $this->getServer()->getLogger()->warning("Config  default spawn value set incorrectly, it must be true or false");
        }
        if ($config->get("teleport-to-default-spawn") == false) {
            if (!is_int($config->get("spawn-x") || $config->get("spawn-y") || $config->get("spawn-y"))) {
                $this->getServer()->getLogger()->warning("Config spawn xyz values set incorrectly, they must be numbers!");
            }
        }
    }

    public function onMove(PlayerMoveEvent $event): void{
        if($event->getTo()->getFloorY() < 0){
            $player = $event->getPlayer();
            $config = $this->getConfig();
            $cplugin = $this->getServer()->getPluginManager()->getPlugin("CombatLogger");

            $level = $this->getServer()->getDefaultLevel();
            $spawn = $level->getSafeSpawn();
            $x = $spawn->getFloorX();
            $y = $spawn->getFloorY();
            $z = $spawn->getFloorZ();

            if($cplugin !== null) {
               if($config->get("disable-if-in-combat")){
                  if (!$cplugin->isTagged($player)) {
                      if ($config->get("teleport-to-default-spawn")) {
                          $player->teleport(new Position($x, $y, $z, $level));
                      }else{
                          $player->teleport(new Vector3($config->get("spawn-x"), $config->get("spawn-y"), $config->get("spawn-z"), $config->get("spawn-level")));
                      }
                    $player->sendMessage($config->get("message"));
                  }
               }
               }else{
                if($config->get("teleport-to-default-spawn")){
                  $player->teleport(new Position($x, $y, $z, $level));
                }else{
                  $player->teleport(new Vector3($config->get("spawn-x"), $config->get("spawn-y"), $config->get("spawn-z"), $config->get("spawn-level")));
                }
                $player->sendMessage($config->get("message"));
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace KumaDev\AlwaysEffect;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\player\Player;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\Effect;

class Main extends PluginBase implements Listener {

    private array $effectNames;
    private int $amplifier;
    private string $mode;
    private array $worlds;
    private int $effectDuration = 3600; // Duration of the effect in seconds
    private int $checkInterval; // Interval to add the effect duration

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->effectNames = array_map('trim', explode(',', $this->getConfig()->get("set-effect", "night_vision")));
        $this->amplifier = $this->getConfig()->get("set-amplifier", 1);
        $this->mode = $this->getConfig()->get("mode", "whitelist");
        $this->worlds = $this->getConfig()->get("worlds", []);
        $this->checkInterval = $this->getConfig()->get("check-interval", 600);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                $this->updateEffects($player);
            }
        }), 20 * $this->checkInterval); // Repeat based on config interval
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->clearEffects($player);
        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player): void {
            $this->updateEffects($player);
        }), 10); // Delay of 0.5 seconds (10 ticks)
    }

    public function onRespawn(PlayerRespawnEvent $event): void {
        $player = $event->getPlayer();
        $this->clearEffects($player);
        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player): void {
            $this->updateEffects($player);
        }), 10); // Delay of 0.5 seconds (10 ticks)
    }

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $this->updateEffects($player);
    }

    private function shouldGiveEffects(Player $player): bool {
        $worldName = $player->getWorld()->getFolderName();
        if ($this->mode === "allworlds") {
            return true;
        } elseif ($this->mode === "whitelist") {
            return in_array($worldName, $this->worlds, true);
        } elseif ($this->mode === "blacklist") {
            return !in_array($worldName, $this->worlds, true);
        }
        return false;
    }

    private function updateEffects(Player $player): void {
        if ($this->shouldGiveEffects($player)) {
            $this->giveEffects($player);
        } else {
            $this->clearEffects($player);
        }
    }

    private function giveEffects(Player $player): void {
        foreach ($this->effectNames as $effectName) {
            $effect = $this->getEffectByName($effectName);
            if ($effect !== null) {
                $existingEffect = $player->getEffects()->get($effect);
                if ($existingEffect === null || $existingEffect->getAmplifier() !== $this->amplifier - 1) {
                    $effectInstance = new EffectInstance($effect, $this->effectDuration * 20, $this->amplifier - 1, false, false); // Particles set to false
                    $player->getEffects()->add($effectInstance);
                }
            }
        }
    }

    private function clearEffects(Player $player): void {
        foreach ($player->getEffects()->all() as $effect) {
            $player->getEffects()->remove($effect->getType());
        }
    }

    private function getEffectByName(string $name): ?Effect {
        switch (strtolower($name)) {
            case "absorption":
                return VanillaEffects::ABSORPTION();
            case "blindness":
                return VanillaEffects::BLINDNESS();
            case "fire_resistance":
                return VanillaEffects::FIRE_RESISTANCE();
            case "haste":
                return VanillaEffects::HASTE();
            case "health_boost":
                return VanillaEffects::HEALTH_BOOST();
            case "hunger":
                return VanillaEffects::HUNGER();
            case "instant_damage":
                return VanillaEffects::INSTANT_DAMAGE();
            case "instant_health":
                return VanillaEffects::INSTANT_HEALTH();
            case "invisibility":
                return VanillaEffects::INVISIBILITY();
            case "jump_boost":
                return VanillaEffects::JUMP_BOOST();
            case "mining_fatigue":
                return VanillaEffects::MINING_FATIGUE();
            case "nausea":
                return VanillaEffects::NAUSEA();
            case "night_vision":
                return VanillaEffects::NIGHT_VISION();
            case "poison":
                return VanillaEffects::POISON();
            case "regeneration":
                return VanillaEffects::REGENERATION();
            case "resistance":
                return VanillaEffects::RESISTANCE();
            case "saturation":
                return VanillaEffects::SATURATION();
            case "slowness":
                return VanillaEffects::SLOWNESS();
            case "speed":
                return VanillaEffects::SPEED();
            case "strength":
                return VanillaEffects::STRENGTH();
            case "water_breathing":
                return VanillaEffects::WATER_BREATHING();
            case "weakness":
                return VanillaEffects::WEAKNESS();
            case "wither":
                return VanillaEffects::WITHER();
            default:
                return null;
        }
    }
}
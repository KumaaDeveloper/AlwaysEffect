## General
AlwaysEffect is a Pocketmine plugin that works to forever apply effects in a set world

## Features
- Applying the effect forever
- Can adjust the selected effect as well as the amplifier
- Can set the world you want to add effects to forever
- Can change modes ranging from “whitelist”, “blacklist” and “allworlds”
  
## Configuration
```yaml
# Configuration for AlwaysEffect plugin

# Change the mode you want, set “whitelist” or “blacklist” or "allworlds"
# If the mode is “whitelist”, then only the world in the config is affected by the AlwaysEffect function.
# If the mode is “blacklist”, then only the world in the config is not affected by the AlwaysEffect function.
# If the mode is “allworlds”, then all worlds will be affected by the AlwaysEffect function.
mode: whitelist

# Define the worlds you want to affect or exclude based on the mode
worlds:
  - lobby
  - world

# Set a time for your world, time will be locked with your set effect
# Set the effect according to the effect you add
set-effect: night_vision, speed
set-amplifier: 1

# Add the time you want
# effect-list:
# - absorption
# - blindness
# - fire_resistance
# - haste
# - health_boost
# - hunger
# - instant_damage
# - instant_health
# - invisibility
# - jump_boost
# - mining_fatigue
# - nausea
# - night_vision
# - poison
# - regeneration
# - resistance
# - saturation
# - slowness
# - speed
# - strength
# - water_breathing
# - weakness
# - wither
```

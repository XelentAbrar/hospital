<template>
  <section aria-label="Current time and date on hospital dashboard" role="region" class="clock-container">
    <div class="clock" aria-hidden="true" ref="clock">
      <div class="center-dot"></div>
      <div class="hand hour" ref="hourHand"></div>
      <div class="hand minute" ref="minuteHand"></div>
      <div class="hand second" ref="secondHand"></div>
      <div class="numbers" aria-hidden="true">
        <span v-for="n in 12" :key="n" :style="getNumberStyle(n)">{{ n }}</span>
      </div>
    </div>
    <div class="date-display" aria-live="polite" aria-atomic="true">{{ formattedDate }}</div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const clock = ref(null);
const hourHand = ref(null);
const minuteHand = ref(null);
const secondHand = ref(null);
const formattedDate = ref('');

function updateClock() {
  const now = new Date();

  const seconds = now.getSeconds() + now.getMilliseconds() / 1000;
  const minutes = now.getMinutes() + seconds / 60;
  const hours = now.getHours() % 12 + minutes / 60;

  const secondDegrees = seconds * 6;  // 360deg/60s
  const minuteDegrees = minutes * 6;  // 360deg/60m
  const hourDegrees = hours * 30;     // 360deg/12h

  if (hourHand.value && minuteHand.value && secondHand.value) {
    hourHand.value.style.transform = `translateX(-50%) rotate(${hourDegrees}deg)`;
    minuteHand.value.style.transform = `translateX(-50%) rotate(${minuteDegrees}deg)`;
    secondHand.value.style.transform = `translateX(-50%) rotate(${secondDegrees}deg)`;
  }

  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  formattedDate.value = now.toLocaleDateString(undefined, options);

  requestAnimationFrame(updateClock);
}

function getNumberStyle(n) {
  const degree = n * 30;
  // Position numbers slightly inside clock radius so numbers do not overlap border
  // This radius is slightly less than clock radius (140px) minus border (12px) and padding
  return {
    transform: `translate(-50%, -50%) rotate(${degree}deg) translateY(-58px) rotate(-${degree}deg)`,
  };
}

onMounted(() => {
  updateClock();
});
</script>

<style scoped>
:root {
  --primary-color: #1e40af; /* Strong blue */
  --secondary-color: #2563eb; /* Accent blue */
  --background: #f3f4f6;
  --clock-face: #ffffff;
  --shadow-color: rgba(30, 64, 175, 0.3);
  --hand-shadow: rgba(30, 64, 175, 0.6);
  --text-color: #1F4E79;
  /* --text-color: #1e40af; */
  --text-shadow-color: rgba(30, 64, 175, 0.3);
}

/* Reset */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.clock-container {
  background: var(--clock-face);
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  /* box-shadow: 0 6px 12px var(--shadow-color); */
  padding: 20px 12px;
  width: 100%;
  height: 100%;
  max-width: 100%;
  text-align: center;
  user-select: none;
  transition: box-shadow 0.3s ease;
  position: relative;
  font-family: 'Inter', sans-serif;
}

/* Clock Face */
.clock {
  position: relative;
  width: 150px;
  height: 150px;
  margin: 0 auto 20px;
  border: 6px solid #1F4E79; /* Remove blue border */
  border-radius: 50%;
  background: #ffffff; /* white background */
  box-shadow:
    inset 0 0 15px rgba(0, 0, 0, 0.05);
}

/* Center Dot */
.center-dot {
  position: absolute;
  width: 12px;
  height: 12px;
  background: var(--secondary-color);
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0 0 10px var(--secondary-color);
  z-index: 12;
}

/* Clock Hands */
.hand {
  position: absolute;
  bottom: 50%;
  left: 50%;
  background: var(--primary-color);
  transform-origin: bottom center;
  border-radius: 6px 6px 0 0;
  transition: transform 0.1s ease-out;
  box-shadow: 0 0 6px var(--primary-color);
  z-index: 10;
}

.hand.hour {
  width: 7px;
  height: 38px;
}

.hand.minute {
  width: 6px;
  height: 45px;
  border-radius: 6px 6px 0 0;
  background: var(--secondary-color);
  box-shadow: 0 0 10px var(--secondary-color);
}

.hand.second {
  width: 4px;
  height: 55px;
  border-radius: 4px 4px 0 0;
  background: #ef4444;
  filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.7));
  z-index: 11;
  transition: none;
}

/* Numbers container */
.numbers {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  user-select: none;
  z-index: 5;
  font-weight: 700;
  font-size: 14px;
  color: var(--primary-color);
  text-shadow: 0 0 5px rgba(37, 99, 235, 0.5);
  font-feature-settings: "tnum";
  font-variant-numeric: tabular-nums;
}

/* Numbers style */
.numbers span {
  position: absolute;
  width: 8px;
  height: 8px;
  text-align: center;
  line-height: 8px;
  top: 50%;
  left: 50%;
  margin-left: -1px;
  margin-top: -2px;
  user-select: none;
  transition: color 0.3s ease;
}

/* Date display */
.date-display {
  font-weight: 700;
  font-size: 1.1rem;
  color:#1F4E79;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  user-select: text;
  margin-top: 8px;
  z-index: 20;
  position: relative;
}

/* Responsive */
@media (max-width: 400px) {
  /* .clock-container {
    width: 150px;
  } */
  .clock {
    width: 140px;
    height: 140px;
  }
  .numbers {
    width: 100px;
    height: 100px;
  }
  .hand.hour {
    width: 8px;
    height: 35px;
  }
  .hand.minute {
    width: 6px;
    height: 45px;
  }
  .hand.second {
    width: 3px;
    height: 50px;
  }
  .date-display {
    font-size: 1rem;
  }
}
</style>

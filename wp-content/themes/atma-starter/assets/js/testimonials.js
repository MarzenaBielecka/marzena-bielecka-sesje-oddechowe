(() => {
  const managerKey = '__atmaTestimonialsManager';
  window[managerKey]?.destroy();

  const manager = {
    cleanups: [],
    clearTimers: [],
    scheduleTimers: [],
    lifecycleController: new AbortController(),
    destroy() {
      this.cleanups.forEach((cleanup) => cleanup());
      this.cleanups = [];
      this.clearTimers = [];
      this.scheduleTimers = [];
      this.lifecycleController.abort();
    },
  };

  window[managerKey] = manager;

  document.querySelectorAll('[data-testimonials-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('.testimonials-grid');
    const previousButton = carousel.querySelector('[data-testimonials-previous]');
    const nextButton = carousel.querySelector('[data-testimonials-next]');

    if (!track) {
      return;
    }

    const controller = new AbortController();
    const { signal } = controller;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let timer = null;
    let paused = false;

    const clearTimer = () => {
      if (timer !== null) {
        window.clearTimeout(timer);
        timer = null;
      }
    };

    const updateNavigation = () => {
      const maximumScroll = track.scrollWidth - track.clientWidth;

      if (previousButton) {
        previousButton.disabled = track.scrollLeft <= 1;
      }

      if (nextButton) {
        nextButton.disabled = track.scrollLeft >= maximumScroll - 1;
      }
    };

    const scheduleNext = () => {
      clearTimer();

      if (paused || reducedMotion.matches || track.scrollWidth <= track.clientWidth) {
        return;
      }

      timer = window.setTimeout(() => {
        const maximumScroll = track.scrollWidth - track.clientWidth;
        const nextScroll = track.scrollLeft + track.clientWidth;

        track.scrollTo({
          left: nextScroll >= maximumScroll - 1 ? 0 : nextScroll,
          behavior: 'smooth',
        });
        scheduleNext();
      }, 30000);
    };

    const moveBySet = (direction) => {
      track.scrollBy({ left: direction * track.clientWidth, behavior: 'smooth' });
      scheduleNext();
    };

    previousButton?.addEventListener('click', () => moveBySet(-1), { signal });
    nextButton?.addEventListener('click', () => moveBySet(1), { signal });

    track.addEventListener('scroll', updateNavigation, { passive: true, signal });
    track.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
        event.preventDefault();
        moveBySet(event.key === 'ArrowLeft' ? -1 : 1);
      }
    }, { signal });

    carousel.addEventListener('pointerenter', () => {
      paused = true;
      clearTimer();
    }, { signal });
    carousel.addEventListener('pointerleave', () => {
      paused = false;
      scheduleNext();
    }, { signal });
    carousel.addEventListener('focusin', () => {
      paused = true;
      clearTimer();
    }, { signal });
    carousel.addEventListener('focusout', (event) => {
      if (!carousel.contains(event.relatedTarget)) {
        paused = false;
        scheduleNext();
      }
    }, { signal });
    reducedMotion.addEventListener('change', scheduleNext, { signal });
    window.addEventListener('resize', () => {
      updateNavigation();
      scheduleNext();
    }, { signal });

    updateNavigation();
    scheduleNext();

    manager.clearTimers.push(clearTimer);
    manager.scheduleTimers.push(scheduleNext);
    manager.cleanups.push(() => {
      clearTimer();
      controller.abort();
    });
  });

  const { signal } = manager.lifecycleController;
  window.addEventListener('pagehide', () => {
    manager.clearTimers.forEach((clearTimer) => clearTimer());
  }, { signal });
  window.addEventListener('pageshow', () => {
    manager.scheduleTimers.forEach((scheduleNext) => scheduleNext());
  }, { signal });
})();

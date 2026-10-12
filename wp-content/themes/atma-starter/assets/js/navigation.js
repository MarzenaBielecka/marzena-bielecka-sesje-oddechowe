(() => {
  const header = document.querySelector('.atma-header');
  const navigation = document.querySelector('#primary-navigation');
  const menuToggle = document.querySelector('.atma-menu-toggle');

  if (!header || !navigation || !menuToggle) {
    return;
  }

  document.documentElement.classList.add('has-js');
  const compactNavigation = window.matchMedia('(max-width: 1200px)');
  const submenuItems = navigation.querySelectorAll('.menu-item-has-children');
  let suppressNextFocus = false;

  const setSubmenuOpen = (item, open, dismissed = false) => {
    const toggle = item.querySelector(':scope > .atma-submenu-toggle');

    if (!toggle) {
      return;
    }

    item.classList.toggle('is-open', open);
    item.classList.toggle('is-dismissed', dismissed);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute(
      'aria-label',
      `${open ? 'Zwiń' : 'Rozwiń'} podmenu: ${item.querySelector(':scope > a').textContent.trim()}`
    );
  };

  const setMenuOpen = (open) => {
    header.classList.toggle('is-menu-open', open);
    menuToggle.setAttribute('aria-expanded', String(open));
    menuToggle.setAttribute('aria-label', open ? 'Zamknij menu' : 'Otwórz menu');

    if (!open) {
      submenuItems.forEach((item) => setSubmenuOpen(item, false));
    }
  };

  menuToggle.addEventListener('click', () => {
    setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
  });

  submenuItems.forEach((item) => {
    const toggle = item.querySelector(':scope > .atma-submenu-toggle');

    if (!toggle) {
      return;
    }

    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') !== 'true';
      setSubmenuOpen(item, open, !open);
    });

    item.addEventListener('pointerenter', () => {
      if (!compactNavigation.matches) {
        setSubmenuOpen(item, true);
      }
    });

    item.addEventListener('pointerleave', () => {
      if (!compactNavigation.matches && !item.contains(document.activeElement)) {
        setSubmenuOpen(item, false);
      }
    });

    item.addEventListener('focusin', () => {
      if (!compactNavigation.matches) {
        if (suppressNextFocus) {
          suppressNextFocus = false;
          return;
        }

        setSubmenuOpen(item, true);
      }
    });

    item.addEventListener('focusout', (event) => {
      if (!compactNavigation.matches && !item.contains(event.relatedTarget) && !item.matches(':hover')) {
        setSubmenuOpen(item, false);
      }
    });
  });

  header.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      const openItem = event.target.closest('.menu-item-has-children.is-open');

      if (openItem) {
        const toggle = openItem.querySelector(':scope > .atma-submenu-toggle');
        setSubmenuOpen(openItem, false, true);

        if (document.activeElement !== toggle) {
          suppressNextFocus = true;
        }

        toggle.focus();
      } else if (compactNavigation.matches && header.classList.contains('is-menu-open')) {
        setMenuOpen(false);
        menuToggle.focus();
      }
    }
  });

  navigation.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      if (compactNavigation.matches) {
        setMenuOpen(false);
      }
    });
  });

  document.addEventListener('click', (event) => {
    if (compactNavigation.matches && header.classList.contains('is-menu-open') && !header.contains(event.target)) {
      setMenuOpen(false);
    }
  });

  compactNavigation.addEventListener('change', () => {
    setMenuOpen(false);
  });

  setMenuOpen(false);
})();

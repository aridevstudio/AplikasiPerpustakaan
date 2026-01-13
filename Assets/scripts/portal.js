 // Configuration
    const defaultConfig = {
      portal_title: 'Portal Perpustakaan Digital',
      portal_subtitle: 'Sistem Manajemen Perpustakaan',
      admin_title: 'Admin Dashboard',
      admin_description: 'Kelola buku, user, dan sistem perpustakaan',
      user_title: 'Portal Pengguna',
      user_description: 'Pinjam dan kelola buku Anda dengan mudah',
      footer_text: '© 2024 Perpustakaan Digital. All rights reserved.',
      primary_color: '#3B82F6',
      bg_color: '#0F172A',
      card_bg: '#FFFFFF',
      admin_color: '#DC2626',
      user_color: '#059669',
      font_family: 'Poppins',
      font_size: 16
    };

    let config = { ...defaultConfig };

    // Element SDK Integration
    async function initApp() {
      if (window.elementSdk) {
        window.elementSdk.init({
          defaultConfig,
          onConfigChange: async (newConfig) => {
            config = { ...defaultConfig, ...newConfig };
            applyConfig();
          },
          mapToCapabilities: (cfg) => ({
            recolorables: [
              {
                get: () => cfg.bg_color || defaultConfig.bg_color,
                set: (v) => { cfg.bg_color = v; window.elementSdk.setConfig({ bg_color: v }); }
              },
              {
                get: () => cfg.card_bg || defaultConfig.card_bg,
                set: (v) => { cfg.card_bg = v; window.elementSdk.setConfig({ card_bg: v }); }
              },
              {
                get: () => cfg.primary_color || defaultConfig.primary_color,
                set: (v) => { cfg.primary_color = v; window.elementSdk.setConfig({ primary_color: v }); }
              },
              {
                get: () => cfg.admin_color || defaultConfig.admin_color,
                set: (v) => { cfg.admin_color = v; window.elementSdk.setConfig({ admin_color: v }); }
              },
              {
                get: () => cfg.user_color || defaultConfig.user_color,
                set: (v) => { cfg.user_color = v; window.elementSdk.setConfig({ user_color: v }); }
              }
            ],
            borderables: [],
            fontEditable: {
              get: () => cfg.font_family || defaultConfig.font_family,
              set: (v) => { cfg.font_family = v; window.elementSdk.setConfig({ font_family: v }); }
            },
            fontSizeable: {
              get: () => cfg.font_size || defaultConfig.font_size,
              set: (v) => { cfg.font_size = v; window.elementSdk.setConfig({ font_size: v }); }
            }
          }),
          mapToEditPanelValues: (cfg) => new Map([
            ['portal_title', cfg.portal_title || defaultConfig.portal_title],
            ['portal_subtitle', cfg.portal_subtitle || defaultConfig.portal_subtitle],
            ['admin_title', cfg.admin_title || defaultConfig.admin_title],
            ['admin_description', cfg.admin_description || defaultConfig.admin_description],
            ['user_title', cfg.user_title || defaultConfig.user_title],
            ['user_description', cfg.user_description || defaultConfig.user_description],
            ['footer_text', cfg.footer_text || defaultConfig.footer_text]
          ])
        });
      }
    }

    function applyConfig() {
      const font = config.font_family || defaultConfig.font_family;
      const baseSize = config.font_size || defaultConfig.font_size;
      const baseFontStack = 'system-ui, sans-serif';
      
      // Apply font
      document.body.style.fontFamily = `${font}, ${baseFontStack}`;
      
      // Apply font sizes proportionally
      document.querySelector('.portal-title').style.fontSize = `${baseSize * 3}px`;
      document.querySelector('.portal-subtitle').style.fontSize = `${baseSize * 1.25}px`;
      document.querySelectorAll('.role-title').forEach(el => el.style.fontSize = `${baseSize * 2}px`);
      document.querySelectorAll('.role-description').forEach(el => el.style.fontSize = `${baseSize}px`);
      document.querySelectorAll('.features-list li').forEach(el => el.style.fontSize = `${baseSize * 0.94}px`);
      document.querySelectorAll('.role-btn').forEach(el => el.style.fontSize = `${baseSize * 1.13}px`);
      document.querySelector('.portal-footer').style.fontSize = `${baseSize * 0.88}px`;
      
      // Update text content
      document.getElementById('portalTitle').textContent = config.portal_title || defaultConfig.portal_title;
      document.getElementById('portalSubtitle').textContent = config.portal_subtitle || defaultConfig.portal_subtitle;
      document.getElementById('adminTitle').textContent = config.admin_title || defaultConfig.admin_title;
      document.getElementById('adminDescription').textContent = config.admin_description || defaultConfig.admin_description;
      document.getElementById('userTitle').textContent = config.user_title || defaultConfig.user_title;
      document.getElementById('userDescription').textContent = config.user_description || defaultConfig.user_description;
      document.getElementById('footerText').textContent = config.footer_text || defaultConfig.footer_text;
      
      // Apply colors
      document.documentElement.style.setProperty('--bg-gradient-start', config.bg_color || defaultConfig.bg_color);
      document.documentElement.style.setProperty('--card-bg', config.card_bg || defaultConfig.card_bg);
      document.documentElement.style.setProperty('--primary-color', config.primary_color || defaultConfig.primary_color);
      document.documentElement.style.setProperty('--admin-color', config.admin_color || defaultConfig.admin_color);
      document.documentElement.style.setProperty('--user-color', config.user_color || defaultConfig.user_color);
    }

    function selectRole(role) {
      const toastEl = document.getElementById('toast');
      const toastMessage = document.getElementById('toastMessage');
      const toast = new bootstrap.Toast(toastEl);
      
      if (role === 'admin') {
        toastMessage.innerHTML = '<i class="bi bi-shield-lock-fill me-2"></i>Mengarahkan ke Admin Dashboard...';
        toastEl.style.background = 'linear-gradient(135deg, #DC2626 0%, #B91C1C 100%)';
      } else {
        toastMessage.innerHTML = '<i class="bi bi-person-circle me-2"></i>Mengarahkan ke Portal Pengguna...';
        toastEl.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
      }
      
      toast.show();
      
      // Simulate navigation - in real app, this would redirect to actual pages
      setTimeout(() => {
        console.log(`Navigating to ${role} portal...`);
      }, 1500);
    }

    // Initialize
    initApp();
    applyConfig();




    (function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9bd5b2e8323db6d2',t:'MTc2ODMxNTc1Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();
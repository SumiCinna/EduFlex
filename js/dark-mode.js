
(function() {
    'use strict';
    
    /**
     * Initialize dark mode on page load
     */
    function initDarkMode() {
        // Check if dark mode is enabled in localStorage
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
        }
        
        // If we're on the settings page, sync the toggle
        syncDarkModeToggle();
    }
    
    /**
     * Toggle dark mode on/off
     */
    function toggleDarkMode() {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);
        
        // Sync toggle if it exists
        syncDarkModeToggle();
        
        // Dispatch custom event for other scripts to listen to
        window.dispatchEvent(new CustomEvent('darkModeChanged', { 
            detail: { enabled: isDarkMode } 
        }));
    }
    
    /**
     * Sync the dark mode toggle switch state (for settings page)
     */
    function syncDarkModeToggle() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            const isDarkMode = document.body.classList.contains('dark-mode');
            if (isDarkMode) {
                darkModeToggle.classList.add('active');
            } else {
                darkModeToggle.classList.remove('active');
            }
        }
    }
    
    /**
     * Setup dark mode toggle listener (for settings page)
     */
    function setupDarkModeToggle() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                toggleDarkMode();
            });
        }
    }
    
    /**
     * Add keyboard shortcut for dark mode (Ctrl/Cmd + Shift + D)
     */
    function setupKeyboardShortcut() {
        document.addEventListener('keydown', function(e) {
            // Check for Ctrl+Shift+D (Windows/Linux) or Cmd+Shift+D (Mac)
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggleDarkMode();
                
                // Show a brief notification
                showDarkModeNotification();
            }
        });
    }
    
    /**
     * Show a brief notification when dark mode is toggled via keyboard
     */
    function showDarkModeNotification() {
        const isDarkMode = document.body.classList.contains('dark-mode');
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: ${isDarkMode ? '#4299e1' : '#2d3748'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = `Dark mode ${isDarkMode ? 'enabled' : 'disabled'}`;
        
        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remove after 2 seconds
        setTimeout(function() {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(function() {
                notification.remove();
                style.remove();
            }, 300);
        }, 2000);
    }
    
    /**
     * Expose global API for dark mode
     */
    window.EduflexDarkMode = {
        toggle: toggleDarkMode,
        isEnabled: function() {
            return document.body.classList.contains('dark-mode');
        },
        enable: function() {
            if (!this.isEnabled()) {
                toggleDarkMode();
            }
        },
        disable: function() {
            if (this.isEnabled()) {
                toggleDarkMode();
            }
        }
    };
    
    /**
     * Initialize everything when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            setupDarkModeToggle();
            setupKeyboardShortcut();
        });
    } else {
        initDarkMode();
        setupDarkModeToggle();
        setupKeyboardShortcut();
    }
    
})();
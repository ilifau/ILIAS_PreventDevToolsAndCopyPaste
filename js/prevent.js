/**
 * prevent.js
 * Blockiert Rechtsklick, Copy&Paste (Strg/Cmd + C/V) und DevTools-Shortcuts (F12, Ctrl+Shift+I/J/C).
 */
document.addEventListener('contextmenu', e => e.preventDefault(), false);

document.addEventListener('keydown', e => {
    const isMac = navigator.platform.toUpperCase().includes('MAC');
    const ctrlOrCmd = isMac ? e.metaKey : e.ctrlKey;
    if (ctrlOrCmd && ['c', 'v'].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }
    if (e.key === 'F12') {
        e.preventDefault();
    }
    if (ctrlOrCmd && e.shiftKey && ['i', 'j', 'c'].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }
});

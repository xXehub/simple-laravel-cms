/**
 * Simple Toast Notification System
 * @author KantorKu SuperApp Team
 */
window.showToast = function (type, message, duration = 3000) {
    // Create toast container if it doesn't exist
    let container = document.getElementById("toast-container");
    if (!container) {
        container = document.createElement("div");
        container.id = "toast-container";
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(container);
    }

    // Create toast element
    const toast = document.createElement("div");
    const toastId = "toast-" + Date.now();
    toast.id = toastId;

    // Toast styles based on type
    const typeStyles = {
        success: "background: #28a745; color: white;",
        error: "background: #dc3545; color: white;",
        warning: "background: #ffc107; color: black;",
        info: "background: #17a2b8; color: white;",
    };

    toast.style.cssText = `
        ${typeStyles[type] || typeStyles.info}
        padding: 12px 16px;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 300px;
        font-size: 14px;
        line-height: 1.4;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        cursor: pointer;
    `;

    // Toast content
    toast.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>${message}</span>
            <span style="margin-left: 10px; font-size: 18px; cursor: pointer;">&times;</span>
        </div>
    `;

    // Add to container
    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.style.opacity = "1";
        toast.style.transform = "translateX(0)";
    });

    // Auto remove after duration
    const removeToast = () => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(100%)";
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    };

    // Click to dismiss
    toast.addEventListener("click", removeToast);

    // Auto dismiss
    setTimeout(removeToast, duration);
};

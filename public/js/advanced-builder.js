// Advanced Builder JavaScript Components
// This file handles tabs, accordion, hero, and footer components

// Component Templates
const componentTemplates = {
    tabs: {
        html: function(config = {}) {
            const tabId = 'tab-' + Date.now();
            const style = config.tab_style || 'nav-tabs';
            const position = config.tab_position || 'horizontal';
            const items = config.items || [
                { title: 'Tab 1', content: 'Content for tab 1' },
                { title: 'Tab 2', content: 'Content for tab 2' }
            ];
            
            let tabsHtml = `<div class="builder-component" data-type="tabs" data-config='${JSON.stringify(config)}'>`;
            
            if (position === 'vertical') {
                tabsHtml += `<div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="${tabId}-tab" role="tablist" aria-orientation="vertical">`;
                
                items.forEach((item, index) => {
                    const isActive = index === 0 ? 'active' : '';
                    tabsHtml += `<button class="nav-link ${isActive}" id="${tabId}-${index}-tab" data-bs-toggle="pill" data-bs-target="#${tabId}-${index}" type="button" role="tab">${item.title}</button>`;
                });
                
                tabsHtml += `</div></div><div class="col-9">
                    <div class="tab-content" id="${tabId}-tabContent">`;
                
                items.forEach((item, index) => {
                    const isActive = index === 0 ? 'show active' : '';
                    tabsHtml += `<div class="tab-pane fade ${isActive}" id="${tabId}-${index}" role="tabpanel">
                        <div class="drop-zone p-3 text-center text-muted border border-2 border-dashed">
                            <small>${item.content}</small>
                        </div>
                    </div>`;
                });
                
                tabsHtml += `</div></div></div>`;
            } else {
                tabsHtml += `<ul class="nav ${style}" id="${tabId}-tab" role="tablist">`;
                
                items.forEach((item, index) => {
                    const isActive = index === 0 ? 'active' : '';
                    tabsHtml += `<li class="nav-item" role="presentation">
                        <button class="nav-link ${isActive}" id="${tabId}-${index}-tab" data-bs-toggle="tab" data-bs-target="#${tabId}-${index}" type="button" role="tab">${item.title}</button>
                    </li>`;
                });
                
                tabsHtml += `</ul><div class="tab-content mt-3" id="${tabId}-tabContent">`;
                
                items.forEach((item, index) => {
                    const isActive = index === 0 ? 'show active' : '';
                    tabsHtml += `<div class="tab-pane fade ${isActive}" id="${tabId}-${index}" role="tabpanel">
                        <div class="drop-zone p-3 text-center text-muted border border-2 border-dashed">
                            <small>${item.content}</small>
                        </div>
                    </div>`;
                });
                
                tabsHtml += `</div>`;
            }
            
            tabsHtml += `</div>`;
            return tabsHtml;
        }
    },
    
    accordion: {
        html: function(config = {}) {
            const accordionId = 'accordion-' + Date.now();
            const style = config.accordion_style || 'accordion';
            const allowMultiple = config.allow_multiple || false;
            const items = config.items || [
                { title: 'Accordion Item 1', content: 'Content for accordion item 1' },
                { title: 'Accordion Item 2', content: 'Content for accordion item 2' }
            ];
            
            let accordionHtml = `<div class="builder-component ${style}" id="${accordionId}" data-type="accordion" data-config='${JSON.stringify(config)}'>`;
            
            items.forEach((item, index) => {
                const isExpanded = index === 0 ? 'true' : 'false';
                const showClass = index === 0 ? 'show' : '';
                const collapseClass = index === 0 ? '' : 'collapsed';
                const parentAttr = allowMultiple ? '' : `data-bs-parent="#${accordionId}"`;
                
                accordionHtml += `
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button ${collapseClass}" type="button" data-bs-toggle="collapse" data-bs-target="#${accordionId}-${index}" aria-expanded="${isExpanded}">
                                ${item.title}
                            </button>
                        </h2>
                        <div id="${accordionId}-${index}" class="accordion-collapse collapse ${showClass}" ${parentAttr}>
                            <div class="accordion-body">
                                <div class="drop-zone p-3 text-center text-muted border border-2 border-dashed">
                                    <small>${item.content}</small>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            
            accordionHtml += `</div>`;
            return accordionHtml;
        }
    },
    
    hero: {
        html: function(config = {}) {
            const style = config.hero_style || 'hero-center';
            const bgType = config.bg_type || 'color';
            const bgColor = config.bg_color || '#0066cc';
            const bgImage = config.bg_image || '';
            const title = config.title || 'Hero Title';
            const subtitle = config.subtitle || 'Hero subtitle text';
            const buttonText = config.button_text || 'Get Started';
            const buttonUrl = config.button_url || '#';
            const height = config.height || '500px';
            const textColor = config.text_color || 'text-white';
            
            let backgroundStyle = '';
            if (bgType === 'image' && bgImage) {
                backgroundStyle = `background-image: url('${bgImage}'); background-size: cover; background-position: center;`;
            } else {
                backgroundStyle = `background-color: ${bgColor};`;
            }
            
            const alignmentClass = style === 'hero-center' ? 'text-center' : 
                                 style === 'hero-right' ? 'text-end' : 'text-start';
            
            return `
                <div class="builder-component hero-section d-flex align-items-center ${textColor}" 
                     style="height: ${height}; ${backgroundStyle}" 
                     data-type="hero" 
                     data-config='${JSON.stringify(config)}'>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 ${alignmentClass}">
                                <h1 class="display-4 fw-bold mb-4">${title}</h1>
                                <p class="lead mb-4">${subtitle}</p>
                                <div class="drop-zone p-3 text-center border border-2 border-dashed border-light">
                                    <a href="${buttonUrl}" class="btn btn-lg btn-primary">${buttonText}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }
    },
    
    footer: {
        html: function(config = {}) {
            const style = config.footer_style || 'simple';
            const bgColor = config.bg_color || 'bg-dark';
            const textColor = config.text_color || 'text-white';
            const copyright = config.copyright || '© 2024 Company Name. All rights reserved.';
            const links = config.links || [];
            
            let footerHtml = `<div class="builder-component ${bgColor} ${textColor} py-4" data-type="footer" data-config='${JSON.stringify(config)}'>
                <div class="container">`;
            
            if (style === 'simple') {
                footerHtml += `
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="mb-2">${copyright}</p>`;
                
                if (links.length > 0) {
                    footerHtml += `<div class="mb-2">`;
                    links.forEach((link, index) => {
                        footerHtml += `<a href="${link.url}" class="text-decoration-none me-3">${link.title}</a>`;
                    });
                    footerHtml += `</div>`;
                }
                
                footerHtml += `</div></div>`;
            } else if (style === 'columns') {
                footerHtml += `
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Company</h5>`;
                
                if (links.length > 0) {
                    footerHtml += `<ul class="list-unstyled">`;
                    links.forEach(link => {
                        footerHtml += `<li><a href="${link.url}" class="text-decoration-none">${link.title}</a></li>`;
                    });
                    footerHtml += `</ul>`;
                }
                
                footerHtml += `
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-0">${copyright}</p>
                        </div>
                    </div>`;
            }
            
            footerHtml += `</div></div>`;
            return footerHtml;
        }
    }
};

// Property form handlers
const propertyFormHandlers = {
    tabs: function(element) {
        const config = JSON.parse(element.getAttribute('data-config') || '{}');
        
        // Populate form
        const form = document.querySelector('#tabs-property-form form');
        if (form) {
            form.querySelector('[name="tab_style"]').value = config.tab_style || 'nav-tabs';
            form.querySelector('[name="tab_position"]').value = config.tab_position || 'horizontal';
            form.querySelector('[name="margin_top"]').value = config.margin_top || '';
            form.querySelector('[name="margin_bottom"]').value = config.margin_bottom || '';
            
            // Populate tab items
            const container = document.getElementById('tab-items-container');
            if (container) {
                container.innerHTML = '';
                const items = config.items || [{ title: 'Tab 1', content: 'Content for tab 1' }];
                
                items.forEach((item, index) => {
                    addTabItem(container, item, index);
                });
                
                // Add tab item handler
                const addBtn = document.getElementById('add-tab-item');
                if (addBtn) {
                    addBtn.onclick = function() {
                        addTabItem(container, { title: 'New Tab', content: 'Tab content' }, container.children.length);
                    };
                }
            }
        }
    },
    
    accordion: function(element) {
        const config = JSON.parse(element.getAttribute('data-config') || '{}');
        
        // Populate form
        const form = document.querySelector('#accordion-property-form form');
        if (form) {
            form.querySelector('[name="accordion_style"]').value = config.accordion_style || 'accordion';
            form.querySelector('[name="allow_multiple"]').checked = config.allow_multiple || false;
            form.querySelector('[name="margin_top"]').value = config.margin_top || '';
            form.querySelector('[name="margin_bottom"]').value = config.margin_bottom || '';
            
            // Populate accordion items
            const container = document.getElementById('accordion-items-container');
            if (container) {
                container.innerHTML = '';
                const items = config.items || [{ title: 'Panel 1', content: 'Panel content 1' }];
                
                items.forEach((item, index) => {
                    addAccordionItem(container, item, index);
                });
                
                // Add accordion item handler
                const addBtn = document.getElementById('add-accordion-item');
                if (addBtn) {
                    addBtn.onclick = function() {
                        addAccordionItem(container, { title: 'New Panel', content: 'Panel content' }, container.children.length);
                    };
                }
            }
        }
    },
    
    hero: function(element) {
        const config = JSON.parse(element.getAttribute('data-config') || '{}');
        
        // Populate form
        const form = document.querySelector('#hero-property-form form');
        if (form) {
            form.querySelector('[name="hero_style"]').value = config.hero_style || 'hero-center';
            form.querySelector('[name="bg_type"]').value = config.bg_type || 'color';
            form.querySelector('[name="bg_color"]').value = config.bg_color || '#0066cc';
            form.querySelector('[name="bg_image"]').value = config.bg_image || '';
            form.querySelector('[name="title"]').value = config.title || 'Hero Title';
            form.querySelector('[name="subtitle"]').value = config.subtitle || 'Hero subtitle text';
            form.querySelector('[name="button_text"]').value = config.button_text || 'Get Started';
            form.querySelector('[name="button_url"]').value = config.button_url || '#';
            form.querySelector('[name="height"]').value = config.height || '500px';
            form.querySelector('[name="text_color"]').value = config.text_color || 'text-white';
            
            // Background type change handler
            const bgTypeSelect = document.getElementById('hero-bg-type');
            const bgColorGroup = document.getElementById('hero-bg-color-group');
            const bgImageGroup = document.getElementById('hero-bg-image-group');
            
            if (bgTypeSelect && bgColorGroup && bgImageGroup) {
                bgTypeSelect.onchange = function() {
                    if (this.value === 'image') {
                        bgColorGroup.style.display = 'none';
                        bgImageGroup.style.display = 'block';
                    } else {
                        bgColorGroup.style.display = 'block';
                        bgImageGroup.style.display = 'none';
                    }
                };
                
                // Trigger change event to set initial state
                bgTypeSelect.dispatchEvent(new Event('change'));
            }
        }
    },
    
    footer: function(element) {
        const config = JSON.parse(element.getAttribute('data-config') || '{}');
        
        // Populate form
        const form = document.querySelector('#footer-property-form form');
        if (form) {
            form.querySelector('[name="footer_style"]').value = config.footer_style || 'simple';
            form.querySelector('[name="bg_color"]').value = config.bg_color || 'bg-dark';
            form.querySelector('[name="text_color"]').value = config.text_color || 'text-white';
            form.querySelector('[name="copyright"]').value = config.copyright || '© 2024 Company Name. All rights reserved.';
            
            // Populate footer links
            const container = document.getElementById('footer-links-container');
            if (container) {
                container.innerHTML = '';
                const links = config.links || [];
                
                links.forEach((link, index) => {
                    addFooterLink(container, link, index);
                });
                
                // Add footer link handler
                const addBtn = document.getElementById('add-footer-link');
                if (addBtn) {
                    addBtn.onclick = function() {
                        addFooterLink(container, { title: 'New Link', url: '#' }, container.children.length);
                    };
                }
            }
        }
    }
};

// Helper functions for dynamic form elements
function addTabItem(container, item, index) {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border rounded p-3 mb-2';
    itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Tab Item ${index + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <label class="form-label">Tab Title</label>
                <input type="text" class="form-control" name="tab_items[${index}][title]" value="${item.title}">
            </div>
            <div class="col-12">
                <label class="form-label">Tab Content</label>
                <textarea class="form-control" name="tab_items[${index}][content]" rows="3">${item.content}</textarea>
            </div>
        </div>
    `;
    
    // Remove item handler
    itemDiv.querySelector('.remove-item').onclick = function() {
        itemDiv.remove();
    };
    
    container.appendChild(itemDiv);
}

function addAccordionItem(container, item, index) {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border rounded p-3 mb-2';
    itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Panel ${index + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <label class="form-label">Panel Title</label>
                <input type="text" class="form-control" name="accordion_items[${index}][title]" value="${item.title}">
            </div>
            <div class="col-12">
                <label class="form-label">Panel Content</label>
                <textarea class="form-control" name="accordion_items[${index}][content]" rows="3">${item.content}</textarea>
            </div>
        </div>
    `;
    
    // Remove item handler
    itemDiv.querySelector('.remove-item').onclick = function() {
        itemDiv.remove();
    };
    
    container.appendChild(itemDiv);
}

function addFooterLink(container, link, index) {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border rounded p-3 mb-2';
    itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Link ${index + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="row">
            <div class="col-6">
                <label class="form-label">Link Title</label>
                <input type="text" class="form-control" name="footer_links[${index}][title]" value="${link.title}">
            </div>
            <div class="col-6">
                <label class="form-label">Link URL</label>
                <input type="url" class="form-control" name="footer_links[${index}][url]" value="${link.url}">
            </div>
        </div>
    `;
    
    // Remove item handler
    itemDiv.querySelector('.remove-item').onclick = function() {
        itemDiv.remove();
    };
    
    container.appendChild(itemDiv);
}

// Export for use in main builder
window.advancedBuilderComponents = {
    componentTemplates,
    propertyFormHandlers
};


function add_css_tab(element) {
    jQuery(".oj_nav_tab_active").removeClass("oj_nav_tab_active").removeClass("active");
    jQuery(element).addClass("oj_nav_tab_active");
}

function copyToClipboard(element) { 
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(jQuery(element).val()).select();
    document.execCommand("copy");
    temp.remove();
}

function copyToClipboard(element1 , element2) { 
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
	$value = jQuery(element2).val()+jQuery(element1).val();
    temp.val($value).select();
    document.execCommand("copy");
    temp.remove();
}


function upgradeBtn()
{
    jQuery("#myModal").css("display","block");
}
function upgradeClose()
{
    jQuery("#myModal").css("display","none");
}
function oauth_back_to_register()
{
    jQuery('#oauth_cancel_form').submit();
}

function oj_oauth_show_proxy_form() {
	jQuery('#submit_proxy1').show();
	jQuery('#proxy_setup1').hide();
}
		
function oj_oauth_hide_proxy_form() {
	jQuery('#submit_proxy1').hide();
	jQuery('#proxy_setup1').show();
	jQuery('#submit_proxy2').hide();
	jQuery('#oj_oauth_registered_page').show();
}
		
function oj_oauth_show_proxy_form2() {
	jQuery('#submit_proxy2').show();
	jQuery('#oj_oauth_registered_page').hide();
}

document.addEventListener('DOMContentLoaded', function() {
    const appSearchInput = document.getElementById('ojAuthAppsearchInput');
    const ojAuthAppsList = document.getElementById('ojAuthAppsList');
    
    if (!appSearchInput || !ojAuthAppsList) return;

    const allLis = ojAuthAppsList.querySelectorAll('li');
    const allHtml = ojAuthAppsList.innerHTML;
    const noAppFoundStr = '<li>No applications found in this category, matching your search query. Please configure yourself.</li>';

    appSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        if (searchTerm === '') {
            ojAuthAppsList.innerHTML = allHtml;
            return;
        }

        const filteredHtml = Array.from(allLis).reduce((html, li) => {
            const appSelector = li.getAttribute('ojAuthAppSelector');
            if (appSelector && appSelector.toLowerCase().includes(searchTerm)) {
                html += li.outerHTML;
            }
            return html;
        }, '');

        ojAuthAppsList.innerHTML = filteredHtml || noAppFoundStr;
    });
});

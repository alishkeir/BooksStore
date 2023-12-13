<div class="ui-pnotify ui-pnotify-mobile-able ui-pnotify-move position-fixed"
     :class="{ 'ui-pnotify-fade-normal ui-pnotify-in ui-pnotify-fade-in': show }"
     style="display: none; width: 300px; right: 20px; top: 50px; cursor: auto;"
     x-data="{ show:false, message: '', type: 'success' }"
     @toast-message.window="show = true; message = $event.detail.message ? $event.detail.message : 'Siker'; type = $event.detail.type ? $event.detail.type : 'success'; setTimeout(() => show=false, 5000);"
     x-show="show"
     x-cloak>
    <div class="brighttheme ui-pnotify-container ui-pnotify-shadow"
         :class="type === 'success' ? 'brighttheme-success' : 'brighttheme-error'"
         role="alert" style="min-height: 16px;">
        <div class="ui-pnotify-closer"
             aria-role="button"
             tabindex="0"
             title="Close"
             style="cursor: pointer;"
             @click="show = false; message = ''">
            <span class="brighttheme-icon-closer"></span>
        </div>
        <div class="ui-pnotify-sticker"
             aria-role="button"
             aria-pressed="false"
             tabindex="0"
             title="Stick"
             style="cursor: pointer; visibility: hidden;">
            <span class="brighttheme-icon-sticker" aria-pressed="false"></span>
        </div>
        <div class="ui-pnotify-icon">
            <span :class="type === 'success' ? 'icon-checkmark3' : 'icon-blocked'"></span>
        </div>
        <h4 class="ui-pnotify-title" x-text="message"></h4>
        <div class="ui-pnotify-text" aria-role="alert" style="display: none;"></div>
        <div class="ui-pnotify-action-bar" style="margin-top: 5px; clear: both; text-align: right; display: none;"></div>
    </div>
</div>

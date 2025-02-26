@extends('admin.layouts.app')

@section('content')
<!-- External Dependencies -->
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>    
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>

<div id="translations-module" class="container mx-auto py-6">
    <!-- Add Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Scoped styles for the translations module only */
        #translations-module {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #334155;
        }
        
        #translations-module .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        #translations-module .card-body {
            padding: 1.5rem;
        }
        
        #translations-module hr {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            border: 0;
            border-top: 1px solid #e2e8f0;
        }
        
        #translations-module .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            cursor: pointer;
            border: 1px solid transparent;
        }
        
        #translations-module .btn-primary {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        #translations-module .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        #translations-module .btn-info {
            background-color: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
        }
        
        #translations-module .btn-info:hover {
            background-color: #0284c7;
            border-color: #0284c7;
        }
        
        #translations-module .btn-default {
            background-color: white;
            color: #475569;
            border-color: #e2e8f0;
        }
        
        #translations-module .btn-default:hover {
            background-color: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        
        #translations-module .form-control {
            display: block;
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #1e293b;
            background-color: white;
            background-clip: padding-box;
            border: 1px solid #cbd5e1;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        #translations-module .form-control:focus {
            color: #1e293b;
            background-color: white;
            border-color: #93c5fd;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        #translations-module .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #334155;
        }
        
        #translations-module .form-group {
            margin-bottom: 1.25rem;
        }
        
        #translations-module .text-muted {
            color: #64748b;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        #translations-module .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #334155;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        
        #translations-module .table th {
            padding: 0.75rem;
            vertical-align: top;
            background-color: #f8fafc;
            font-weight: 600;
            text-align: left;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        
        #translations-module .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #e2e8f0;
        }
        
        #translations-module .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        #translations-module .alert {
            position: relative;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }
        
        #translations-module .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        
        #translations-module .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
        
        /* X-editable styling overrides */
        .editable-container.editable-inline {
            display: inline-block;
            vertical-align: middle;
            max-width: 100%;
        }
        
        .editable-input {
            display: flex;
            flex-direction: column;
        }
        
        .editable-input textarea {
            min-width: 200px;
            min-height: 60px;
        }
        
        .editable-buttons {
            margin-top: 0.5rem;
            display: flex;
        }
        
        .editable-buttons button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            margin-right: 0.25rem;
        }
        
        .editable-buttons button:hover {
            background-color: #2563eb;
        }
        
        .editable-buttons button[type="button"] {
            background-color: #ef4444;
        }
        
        .editable-buttons button[type="button"]:hover {
            background-color: #dc2626;
        }
        
        .popover {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 400px !important;
        }
        
        .popover-title {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }
        
        .popover-content {
            padding: 1rem;
        }
        
        /* Editable field styling */
        #translations-module .editable {
            display: block;
            padding: 0.5rem;
            min-height: 2.5rem;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            transition: all 0.15s ease-in-out;
            line-height: 1.5;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: pre-wrap;
        }
        
        #translations-module .editable:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: #cbd5e1;
            cursor: pointer;
        }
        
        #translations-module .editable.status-1 {
            font-weight: 600;
            color: #1e293b;
        }
        
        #translations-module .editable.status-0 {
            color: #64748b;
            background-color: rgba(241, 245, 249, 0.5);
        }
        
        #translations-module .delete-key {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }
        
        #translations-module .delete-key:hover {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        #translations-module .enable-auto-translate-group {
            background-color: #8b5cf6;
            color: white;
            border-color: #8b5cf6;
        }
        
        #translations-module .enable-auto-translate-group:hover {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
    </style>

    <!-- Custom jQuery-UJS Script -->
    <script>
        (function(e,t){if(e.rails!==t){e.error("jquery-ujs has already been loaded!")}var n;var r=e(document);e.rails=n={linkClickSelector:"a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",buttonClickSelector:"button[data-remote], button[data-confirm]",inputChangeSelector:"select[data-remote], input[data-remote], textarea[data-remote]",formSubmitSelector:"form",formInputClickSelector:"form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",disableSelector:"input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",enableSelector:"input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",requiredInputSelector:"input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",fileInputSelector:"input[type=file]",linkDisableSelector:"a[data-disable-with]",buttonDisableSelector:"button[data-remote][data-disable-with]",CSRFProtection:function(t){var n=e('meta[name="csrf-token"]').attr("content");if(n)t.setRequestHeader("X-CSRF-Token",n)},refreshCSRFTokens:function(){var t=e("meta[name=csrf-token]").attr("content");var n=e("meta[name=csrf-param]").attr("content");e('form input[name="'+n+'"]').val(t)},fire:function(t,n,r){var i=e.Event(n);t.trigger(i,r);return i.result!==false},confirm:function(e){return confirm(e)},ajax:function(t){return e.ajax(t)},href:function(e){return e.attr("href")},handleRemote:function(r){var i,s,o,u,a,f,l,c;if(n.fire(r,"ajax:before")){u=r.data("cross-domain");a=u===t?null:u;f=r.data("with-credentials")||null;l=r.data("type")||e.ajaxSettings&&e.ajaxSettings.dataType;if(r.is("form")){i=r.attr("method");s=r.attr("action");o=r.serializeArray();var h=r.data("ujs:submit-button");if(h){o.push(h);r.data("ujs:submit-button",null)}}else if(r.is(n.inputChangeSelector)){i=r.data("method");s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else if(r.is(n.buttonClickSelector)){i=r.data("method")||"get";s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else{i=r.data("method");s=n.href(r);o=r.data("params")||null}c={type:i||"GET",data:o,dataType:l,beforeSend:function(e,i){if(i.dataType===t){e.setRequestHeader("accept","*/*;q=0.5, "+i.accepts.script)}if(n.fire(r,"ajax:beforeSend",[e,i])){r.trigger("ajax:send",e)}else{return false}},success:function(e,t,n){r.trigger("ajax:success",[e,t,n])},complete:function(e,t){r.trigger("ajax:complete",[e,t])},error:function(e,t,n){r.trigger("ajax:error",[e,t,n])},crossDomain:a};if(f){c.xhrFields={withCredentials:f}}if(s){c.url=s}return n.ajax(c)}else{return false}},handleMethod:function(r){var i=n.href(r),s=r.data("method"),o=r.attr("target"),u=e("meta[name=csrf-token]").attr("content"),a=e("meta[name=csrf-param]").attr("content"),f=e('<form method="post" action="'+i+'"></form>'),l='<input name="_method" value="'+s+'" type="hidden" />';if(a!==t&&u!==t){l+='<input name="'+a+'" value="'+u+'" type="hidden" />'}if(o){f.attr("target",o)}f.hide().append(l).appendTo("body");f.submit()},formElements:function(t,n){return t.is("form")?e(t[0].elements).filter(n):t.find(n)},disableFormElements:function(t){n.formElements(t,n.disableSelector).each(function(){n.disableFormElement(e(this))})},disableFormElement:function(e){var t=e.is("button")?"html":"val";e.data("ujs:enable-with",e[t]());e[t](e.data("disable-with"));e.prop("disabled",true)},enableFormElements:function(t){n.formElements(t,n.enableSelector).each(function(){n.enableFormElement(e(this))})},enableFormElement:function(e){var t=e.is("button")?"html":"val";if(e.data("ujs:enable-with"))e[t](e.data("ujs:enable-with"));e.prop("disabled",false)},allowAction:function(e){var t=e.data("confirm"),r=false,i;if(!t){return true}if(n.fire(e,"confirm")){r=n.confirm(t);i=n.fire(e,"confirm:complete",[r])}return r&&i},blankInputs:function(t,n,r){var i=e(),s,o,u=n||"input,textarea",a=t.find(u);a.each(function(){s=e(this);o=s.is("input[type=checkbox],input[type=radio]")?s.is(":checked"):s.val();if(!o===!r){if(s.is("input[type=radio]")&&a.filter('input[type=radio]:checked[name="'+s.attr("name")+'"]').length){return true}i=i.add(s)}});return i.length?i:false},nonBlankInputs:function(e,t){return n.blankInputs(e,t,true)},stopEverything:function(t){e(t.target).trigger("ujs:everythingStopped");t.stopImmediatePropagation();return false},disableElement:function(e){e.data("ujs:enable-with",e.html());e.html(e.data("disable-with"));e.bind("click.railsDisable",function(e){return n.stopEverything(e)})},enableElement:function(e){if(e.data("ujs:enable-with")!==t){e.html(e.data("ujs:enable-with"));e.removeData("ujs:enable-with")}e.unbind("click.railsDisable")}};if(n.fire(r,"rails:attachBindings")){e.ajaxPrefilter(function(e,t,r){if(!e.crossDomain){n.CSRFProtection(r)}});r.delegate(n.linkDisableSelector,"ajax:complete",function(){n.enableElement(e(this))});r.delegate(n.buttonDisableSelector,"ajax:complete",function(){n.enableFormElement(e(this))});r.delegate(n.linkClickSelector,"click.rails",function(r){var i=e(this),s=i.data("method"),o=i.data("params"),u=r.metaKey||r.ctrlKey;if(!n.allowAction(i))return n.stopEverything(r);if(!u&&i.is(n.linkDisableSelector))n.disableElement(i);if(i.data("remote")!==t){if(u&&(!s||s==="GET")&&!o){return true}var a=n.handleRemote(i);if(a===false){n.enableElement(i)}else{a.error(function(){n.enableElement(i)})}return false}else if(i.data("method")){n.handleMethod(i);return false}});r.delegate(n.buttonClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);if(r.is(n.buttonDisableSelector))n.disableFormElement(r);var i=n.handleRemote(r);if(i===false){n.enableFormElement(r)}else{i.error(function(){n.enableFormElement(r)})}return false});r.delegate(n.inputChangeSelector,"change.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);n.handleRemote(r);return false});r.delegate(n.formSubmitSelector,"submit.rails",function(r){var i=e(this),s=i.data("remote")!==t,o,u;if(!n.allowAction(i))return n.stopEverything(r);if(i.attr("novalidate")==t){o=n.blankInputs(i,n.requiredInputSelector);if(o&&n.fire(i,"ajax:aborted:required",[o])){return n.stopEverything(r)}}if(s){u=n.nonBlankInputs(i,n.fileInputSelector);if(u){setTimeout(function(){n.disableFormElements(i)},13);var a=n.fire(i,"ajax:aborted:file",[u]);if(!a){setTimeout(function(){n.enableFormElements(i)},13)}return a}n.handleRemote(i);return false}else{setTimeout(function(){n.disableFormElements(i)},13)}});r.delegate(n.formInputClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);var i=r.attr("name"),s=i?{name:i,value:r.val()}:null;r.closest("form").data("ujs:submit-button",s)});r.delegate(n.formSubmitSelector,"ajax:send.rails",function(t){if(this==t.target)n.disableFormElements(e(this))});r.delegate(n.formSubmitSelector,"ajax:complete.rails",function(t){if(this==t.target)n.enableFormElements(e(this))});e(function(){n.refreshCSRFTokens()})}})(jQuery)
    </script>

    <!-- Custom jQuery Initialization -->
    <script>
        jQuery(document).ready(function($){
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    settings.data += "&_token=<?php echo csrf_token() ?>";
                }
            });

            $('.editable').editable({
                mode: 'inline',
                showbuttons: 'bottom',
                inputclass: 'form-control',
                placement: 'right'
            }).on('hidden', function(e, reason){
                var locale = $(this).data('locale');
                if(reason === 'save'){
                    $(this).removeClass('status-0').addClass('status-1');
                }
                if(reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                    setTimeout(function() {
                        $next.editable('show');
                    }, 300);
                }
            });

            $('.group-select').on('change', function(){
                var group = $(this).val();
                if (group) {
                window.location.href = '{{route('get.view')}}/'+$(this).val();
                } else {
                window.location.href = '{{route('admin.languages.index')}}';
                }
            });

            $("a.delete-key").on('confirm:complete',function(event,result){
                if(result) {
                    var row = $(this).closest('tr');
                    var url = $(this).attr('href');
                    var id = row.attr('id');
                    $.post(url, {id: id}, function(){
                        row.remove();
                    });
                }
                return false;
            });

            $('.form-import').on('ajax:success', function (e, data) {
                $('div.success-import strong.counter').text(data.counter);
                $('div.success-import').slideDown();
                window.location.reload();
            });

            $('.form-find').on('ajax:success', function (e, data) {
                $('div.success-find strong.counter').text(data.counter);
                $('div.success-find').slideDown();
                window.location.reload();
            });

            $('.form-publish').on('ajax:success', function (e, data) {
                $('div.success-publish').slideDown();
            });

            $('.form-publish-all').on('ajax:success', function (e, data) {
                $('div.success-publish-all').slideDown();
            });
            
            $('.enable-auto-translate-group').click(function (event) {
                event.preventDefault();
                $('.autotranslate-block-group').removeClass('hidden');
                $('.enable-auto-translate-group').addClass('hidden');
            });
            
            $('#base-locale').change(function (event) {
                $.cookie('base_locale', $(this).val());
            });
            
            if (typeof $.cookie('base_locale') !== 'undefined') {
                $('#base-locale').val($.cookie('base_locale'));
            }

            // Add search functionality
            $('#search-translations').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.translation-row').filter(function() {
                    $(this).toggle($(this).find('.translation-key').text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">{{__('Translations Management')}}</h2>

        <div>
            @if(!isset($group))
                <div class="flex space-x-2">
                <form class="form-find" method="POST" action="{{route('post.find')}}" data-remote="true" role="form" data-confirm="Are you sure you want to scan you app folder? All found translation keys will be added to the database.">
                        @csrf
                        <button type="submit" class="btn btn-info" data-disable-with="Searching..">
                            <i class="fa fa-search mr-1"></i> {{__('Find Keys')}}
                        </button>
                    </form>

                <form class="form-publish-all" method="POST" action="{{route('post.publish', '*')}}" data-remote="true" role="form" data-confirm="Are you sure you want to publish all translations group? This will overwrite existing language files.">
                        @csrf
                        <button type="submit" class="btn btn-primary" data-disable-with="Publishing..">
                            <i class="fa fa-globe mr-1"></i> {{__('Publish All')}}
                        </button>
                    </form>
                </div>
            @else
                <div class="flex space-x-2">
                <form class="form-publish" method="POST" action="{{route('post.publish', $group)}}" data-remote="true" role="form" data-confirm="Are you sure you want to publish the translations group '<?php echo $group ?>? This will overwrite existing language files.">
                        @csrf
                        <button type="submit" class="btn btn-info" data-disable-with="Publishing..">
                            <i class="fa fa-save mr-1"></i> {{__('Publish Translations')}}
                        </button>
                    </form>

                <a href="{{route('admin.languages.index')}}" class="btn btn-default">
                        <i class="fa fa-arrow-left mr-1"></i> {{__('Back')}}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-body">
            <!-- Alert messages -->
            <div class="alert alert-success success-import" style="display:none;">
                <p>{{__('Done importing, processed')}} <strong class="counter">N</strong> {{__('items')}}! {{__('Reload this page to refresh the groups')}}!</p>
            </div>
            <div class="alert alert-success success-find" style="display:none;">
                <p>{{__('Done searching for translations, found')}} <strong class="counter">N</strong> {{__('items')}}!</p>
            </div>
            <div class="alert alert-success success-publish" style="display:none;">
                <p>{{__('Done publishing the translations for group')}} '<?php echo $group ?? '' ?>'!</p>
            </div>
            <div class="alert alert-success success-publish-all" style="display:none;">
                <p>{{__('Done publishing the translations for all group')}}!</p>
            </div>
            <?php if(Session::has('successPublish')) : ?>
                <div class="alert alert-info">
                    <?php echo Session::get('successPublish'); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="form-group">
                    <form role="form" method="POST" action="{{route('post.add.group')}}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">{{__('Translation Group')}}</label>
                                <p class="text-muted">{{__('Choose a group to display the group translations. If no groups are visible, make sure you have run the migrations and imported the translations')}}.</p>
                                <select name="group" id="group" class="form-control group-select">
                                    <?php foreach($groups as $key => $value): ?>
                                        <option value="<?php echo $key ?>"<?php echo $key == ($group ?? '') ? ' selected':'' ?>><?php echo $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('New Group')}}</label>
                                <input type="text" class="form-control" name="new-group" placeholder="{{__('Enter a new group name')}}" />
                                <small class="text-muted">{{__('Enter a new group name and start edit translations in that group')}}</small>
                            </div>
                            <button type="submit" name="add-group" class="btn btn-primary">
                                {{__('Add and Edit Keys')}}
                            </button>
                        </form>
                    </div>
                </div>

                <div>
                    <?php if(!isset($group)) : ?>
                        <div class="form-group">
                            <label class="form-label">{{__('Import Options')}}</label>
                        <form class="form-import" method="POST" action="{{route('post.import')}}" data-remote="true" role="form">
                                @csrf
                                <div class="flex items-center space-x-2">
                                    <select name="replace" class="form-control">
                                        <option value="0">{{__('Append New Translation')}}</option>
                                        <option value="1">{{__('Replace Existing Translation')}}</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" data-disable-with="Loading..">
                                        {{__('Import Groups')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                        <form action="{{route('post.add', array($group))}}" method="POST" role="form">
                                @csrf
                                <label class="form-label">{{__('Add New Keys')}}</label>
                                <textarea class="form-control" rows="3" name="keys" placeholder="{{__('Add 1 key per line, without the group prefix')}}"></textarea>
                                <button type="submit" class="btn btn-primary mt-4">
                                    {{__('Add Keys')}}
                                </button>
                            </form>
                        </div>

                        <div class="mt-4">
                            <button class="btn enable-auto-translate-group">
                                <i class="fa fa-language mr-1"></i> {{__('Use Auto Translate')}}
                            </button>

                        <form class="form-add-locale autotranslate-block-group hidden mt-4" method="POST" role="form" action="{{route('post.translation.missing')}}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="form-group">
                                            <label for="base-locale" class="form-label">{{__('Base Locale')}}</label>
                                            <select name="base-locale" id="base-locale" class="form-control">
                                                <?php foreach ($locales as $locale): ?>
                                                <option value="<?= $locale ?>"><?= $locale ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label for="new-locale" class="form-label">{{__('Target Locale')}}</label>
                                            <input type="text" name="new-locale" class="form-control" id="new-locale" placeholder="{{__('Enter target locale key')}}" />
                                        </div>
                                    </div>
                                </div>

                                <?php if(!config('laravel_google_translate.google_translate_api_key')): ?>
                                <div class="bg-gray-100 rounded p-3 mb-4 text-sm">
                                    <small>{{__('If you would like to use Google Translate API, install tanmuhittin/laravel-google-translate and enter your Google Translate API key to config file laravel_google_translate')}}</small>
                                </div>
                                <?php endif; ?>

                                <input type="hidden" name="with-translations" value="1">
                                <input type="hidden" name="file" value="<?= $group ?? '' ?>">
                                <button type="submit" class="btn btn-primary" data-disable-with="Adding..">
                                    {{__('Auto Translate Missing')}}
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(isset($group)): ?>
            <hr>

            <div class="flex justify-between items-center mb-4 mt-6">
                <h4 class="text-lg font-medium">{{__('Total')}}: <?= $numTranslations ?>, {{__('Changed')}}: <?= $numChanged ?></h4>

                <div>
                    <input type="text" id="search-translations" class="form-control w-64" placeholder="{{__('Search translations...')}}" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="15%">{{__('Key')}}</th>
                            <?php foreach ($locales as $locale): ?>
                            <th><?= $locale ?></th>
                            <?php endforeach; ?>
                            <?php if ($deleteEnabled): ?>
                            <th width="5%" class="text-center">{{__('Actions')}}</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($translations as $key => $translation): ?>
                        <tr id="<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>" class="translation-row">
                            <td class="translation-key font-medium"><?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?></td>
                            <?php foreach ($locales as $locale): ?>
                            <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>
                            <td>
                                <a href="#edit" class="editable status-<?php echo $t ? $t->status : 0 ?> locale-<?php echo $locale ?>" 
                                   data-locale="<?php echo $locale ?>" 
                                   data-name="<?php echo $locale . "|" . htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>" 
                                   data-type="textarea" 
                                   data-pk="<?php echo $t ? $t->id : 0 ?>" 
                                   data-url="<?php echo $editUrl ?>" 
                                   data-title="{{__('Enter translation')}}">
                                    <?php echo $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?>
                                </a>
                            </td>
                            <?php endforeach; ?>
                            <?php if ($deleteEnabled): ?>
                            <td class="text-center">
                            <a href="{{route('post.delete', [$group, $key])}}" 
                                   class="delete-key" 
                                   data-confirm="{{__('Are you sure you want to delete the translations for')}} '<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>'?">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
@endsection

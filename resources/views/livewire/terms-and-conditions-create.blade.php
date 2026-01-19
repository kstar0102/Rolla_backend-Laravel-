<div>
    <title>Create Terms and Conditions</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/terms-and-conditions">Terms and Conditions</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
            <h2 class="h4">Create Terms and Conditions</h2>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save" onsubmit="saveTinyMCEContent();">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Terms and Conditions Information</h2>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title">Title</label>
                            <input wire:model="title" class="form-control" id="title" type="text" placeholder="Enter title (e.g., Terms and Conditions)">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="content">Content</label>
                            <div wire:ignore>
                                <textarea class="form-control" id="content" rows="20" placeholder="Enter Terms and Conditions content"></textarea>
                            </div>
                            <input type="hidden" wire:model="content" id="content_hidden">
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">
                                <strong>Word-style Editor:</strong> Use the toolbar above to format your text (bold, bullet points, headings, etc.). Content will be automatically saved as HTML.
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3" style="display: none;">
                            <label for="content_type">Content Type</label>
                            <select wire:model="content_type" class="form-control" id="content_type">
                                <option value="html" selected>HTML</option>
                            </select>
                            @error('content_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pdf_url">PDF URL (Optional)</label>
                            <input wire:model="pdf_url" class="form-control" id="pdf_url" type="url" placeholder="https://example.com/terms.pdf">
                            @error('pdf_url') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Optional: URL to a PDF version of the Terms and Conditions.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input wire:model="is_active" class="form-check-input" type="checkbox" id="is_active">
                                <label class="form-check-label" for="is_active">Set as Active (will deactivate other terms)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Create Terms and Conditions</span>
            <span wire:loading wire:target="save">Creating...</span>
        </button>
        <a href="/terms-and-conditions" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        // Wait for TinyMCE to load
        function waitForTinyMCE(callback) {
            if (typeof tinymce !== 'undefined' && tinymce.init) {
                callback();
            } else {
                setTimeout(function() {
                    waitForTinyMCE(callback);
                }, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            waitForTinyMCE(function() {
                initializeTinyMCE();
            });
        });

        // Prevent Livewire from clearing the editor on updates
        document.addEventListener('livewire:load', function() {
            waitForTinyMCE(function() {
                if (!tinymce.get('content')) {
                    initializeTinyMCE();
                }
            });
        });

        // CRITICAL: Save editor content before Livewire updates
        document.addEventListener('livewire:before-update', function() {
            var editor = tinymce.get('content');
            if (editor) {
                var content = editor.getContent();
                editor.save();
                // Store in a way that survives Livewire updates
                sessionStorage.setItem('tinymce_content', content);
            }
        });

        // CRITICAL: Restore editor content after Livewire updates
        document.addEventListener('livewire:after-update', function() {
            var editor = tinymce.get('content');
            if (editor) {
                var savedContent = sessionStorage.getItem('tinymce_content');
                if (savedContent && savedContent !== editor.getContent()) {
                    editor.setContent(savedContent);
                }
            }
        });

        // Function to save TinyMCE content before form submission
        function saveTinyMCEContent() {
            if (typeof tinymce !== 'undefined') {
                var editor = tinymce.get('content');
                if (editor) {
                    var content = editor.getContent();
                    editor.save();
                    // Update hidden input
                    var hiddenInput = document.getElementById('content_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                    }
                    // Force update Livewire
                    @this.set('content', content, true);
                    @this.set('content_type', 'html', true);
                }
            }
        }

        function initializeTinyMCE() {
            // Check if editor already exists
            if (tinymce.get('content')) {
                return; // Editor already initialized
            }

            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
                    'paste'
                ],
                toolbar: 'undo redo | formatselect | fontsize | ' +
                    'bold italic underline | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 60pt 72pt',
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; } ul ul { list-style-type: circle; } ul ul ul { list-style-type: square; }',
                // Configure paste plugin - preserve lists and formatting
                paste_as_text: false,
                paste_auto_cleanup_on_paste: false,
                paste_remove_styles: false,
                paste_remove_styles_if_webkit: false,
                paste_strip_class_attributes: 'none',
                paste_retain_style_properties: 'all',
                paste_merge_formats: true,
                paste_enable_default_filters: false,
                // Better list handling - preserve nested lists
                forced_root_block: 'p',
                forced_root_block_attrs: {},
                convert_newlines_to_brs: false,
                remove_linebreaks: false,
                // Ensure lists are properly formatted with nested support
                advlist_bullet_styles: 'disc circle square',
                advlist_number_styles: 'lower-alpha lower-roman upper-alpha upper-roman',
                // Enable nested list indentation
                indent_use_margin: false,
                indent_bottom: true,
                // Ensure proper list behavior
                list_indent_on_tab: true,
                // Preserve list structure when pasting
                paste_preprocess: function(plugin, args) {
                    // Ensure nested lists are preserved
                    var content = args.content;
                    
                    // Fix nested list structure - ensure nested <ul> is inside <li>
                    // Pattern: <li>text<ul> should become <li>text<ul> (already correct)
                    // Pattern: <li>text</li><ul> should become <li>text<ul>
                    content = content.replace(/<\/li>\s*<ul/gi, '<ul');
                    content = content.replace(/<li([^>]*)>([^<]*)<ul/gi, '<li$1>$2<ul');
                    
                    // Ensure proper closing tags for nested lists
                    content = content.replace(/<\/ul>\s*<\/li>/gi, '</ul></li>');
                    
                    // Fix any orphaned list items
                    content = content.replace(/<li([^>]*)>\s*<\/li>/gi, '');
                    
                    args.content = content;
                },
                paste_postprocess: function(plugin, args) {
                    // After paste, ensure nested lists are properly formatted
                    var editor = plugin.editor;
                    var content = args.node;
                    
                    // Find all nested lists and ensure they're properly structured
                    var nestedLists = content.querySelectorAll('ul ul, ol ol, ul ol, ol ul');
                    nestedLists.forEach(function(nestedList) {
                        // Ensure parent is a list item
                        var parent = nestedList.parentElement;
                        if (parent && parent.tagName !== 'LI') {
                            var li = document.createElement('li');
                            parent.insertBefore(li, nestedList);
                            li.appendChild(nestedList);
                        }
                    });
                },
                setup: function(editor) {
                    // Restore saved content on init
                    editor.on('init', function() {
                        var savedContent = sessionStorage.getItem('tinymce_content');
                        if (savedContent) {
                            editor.setContent(savedContent);
                        }
                    });
                    
                    // Ensure proper list formatting when list buttons are clicked
                    editor.on('ExecCommand', function(e) {
                        // When bullet or numbered list is applied
                        if (e.command === 'InsertUnorderedList' || e.command === 'InsertOrderedList') {
                            setTimeout(function() {
                                // Ensure paragraphs are converted to list items
                                var body = editor.getBody();
                                var paragraphs = body.querySelectorAll('p');
                                paragraphs.forEach(function(p) {
                                    // If paragraph is inside a list, convert to list item
                                    var parent = p.parentElement;
                                    if (parent && (parent.tagName === 'UL' || parent.tagName === 'OL')) {
                                        var li = document.createElement('li');
                                        li.innerHTML = p.innerHTML;
                                        parent.replaceChild(li, p);
                                    }
                                });
                                syncContent(editor);
                            }, 100);
                        }
                    });
                    
                    // Handle indentation properly for lists
                    editor.on('keydown', function(e) {
                        // Tab key should indent list items, not paragraphs
                        if (e.keyCode === 9 && !e.shiftKey) { // Tab key
                            var selection = editor.selection;
                            var node = selection.getNode();
                            
                            // If we're in a list item, let TinyMCE handle it naturally
                            if (node.tagName === 'LI' || node.closest('li')) {
                                return; // Let default behavior handle list indentation
                            }
                        }
                    });
                    
                    // Sync content to Livewire after paste completes
                    editor.on('paste', function(e) {
                        // Use longer timeout to ensure paste is fully processed
                        setTimeout(function() {
                            syncContent(editor);
                        }, 500);
                    });
                    
                    // Also sync on any content change (but less frequently)
                    var changeTimeout;
                    editor.on('change', function() {
                        clearTimeout(changeTimeout);
                        changeTimeout = setTimeout(function() {
                            syncContent(editor);
                        }, 1000); // Wait 1 second before syncing to avoid too many updates
                    });
                    
                    editor.on('blur', function() {
                        syncContent(editor);
                    });
                    
                    // Sync on keyup (debounced - less frequent)
                    var keyupTimeout;
                    editor.on('keyup', function() {
                        clearTimeout(keyupTimeout);
                        keyupTimeout = setTimeout(function() {
                            syncContent(editor);
                        }, 2000); // Wait 2 seconds to reduce Livewire updates
                    });
                }
            });
        }

        function syncContent(editor) {
            try {
                var content = editor.getContent();
                editor.save();
                
                // Store in sessionStorage to survive Livewire updates
                sessionStorage.setItem('tinymce_content', content);
                
                // Update textarea
                var textarea = document.getElementById('content');
                if (textarea) {
                    textarea.value = content;
                }
                
                // Update hidden input WITHOUT triggering Livewire update immediately
                var hiddenInput = document.getElementById('content_hidden');
                if (hiddenInput) {
                    hiddenInput.value = content;
                }
                
                // Update Livewire with skipWire to prevent re-render
                @this.set('content', content, true);
                @this.set('content_type', 'html', true);
            } catch (e) {
                console.error('Error syncing content:', e);
            }
        }
    </script>
</div>

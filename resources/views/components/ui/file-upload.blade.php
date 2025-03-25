@props([
    'name' => 'file',
    'id' => null,
    'label' => 'Upload file',
    'multiple' => false,
    'accept' => '',
    'placeholder' => 'Drag and drop files here or click to browse',
    'helper' => '',
    'error' => '',
    'required' => false,
    'disabled' => false,
    'maxFileSize' => 5, // In MB
    'maxFiles' => 5,
    'dropzoneHeight' => 'h-32',
    'preview' => true,
    'showFileDetails' => true,
    'rounded' => 'md',
    'progressBar' => true,
    'wire:model' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $error ? true : false;
    
    // Rounded classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Format accepted file types for display
    $acceptedTypes = '';
    if ($accept) {
        $types = explode(',', $accept);
        $acceptedTypes = implode(', ', array_map(function($type) {
            return str_replace('.', '', str_replace('*', '', trim($type)));
        }, $types));
    }
    
    // Determine if it's a livewire attachment
    $isLivewireAttachment = isset($attributes['wire:model']) || 
                           isset($attributes['wire:model.defer']) || 
                           isset($attributes['wire:model.lazy']);
@endphp

<div 
    x-data="{ 
        files: [],
        filenames: [],
        fileErrors: {},
        uploading: false,
        uploadProgress: 0,
        dragover: false,
        
        init() {
            this.$watch('files', value => {
                this.filenames = Array.from(value).map(file => file.name);
                
                // Validate files
                this.validateFiles();
            });
        },
        
        validateFiles() {
            this.fileErrors = {};
            
            // Check file size
            const maxSizeBytes = {{ $maxFileSize }} * 1024 * 1024;
            
            Array.from(this.files).forEach((file, index) => {
                if (file.size > maxSizeBytes) {
                    this.fileErrors[file.name] = `File exceeds maximum size of {{ $maxFileSize }}MB`;
                }
            });
            
            // Check number of files
            if ({{ $multiple ? 'true' : 'false' }} && this.files.length > {{ $maxFiles }}) {
                this.fileErrors['max_files'] = 'Maximum {{ $maxFiles }} files allowed';
            }
        },
        
        removeFile(filename) {
            const dt = new DataTransfer();
            const filteredFiles = Array.from(this.files).filter(file => file.name !== filename);
            
            filteredFiles.forEach(file => dt.items.add(file));
            
            this.files = dt.files;
            this.$refs.input.files = dt.files;
            
            // If using Livewire, dispatch file removal
            @if($isLivewireAttachment)
                const event = new Event('input', {
                    bubbles: true,
                    cancelable: true,
                });
                this.$refs.input.dispatchEvent(event);
            @endif
            
            delete this.fileErrors[filename];
        },
        
        getFileIcon(filename) {
            const extension = filename.split('.').pop().toLowerCase();
            
            const icons = {
                pdf: '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
                doc: '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
                docx: '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
                xls: '<svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
                xlsx: '<svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
                jpg: '<svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
                jpeg: '<svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
                png: '<svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
                gif: '<svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
                zip: '<svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 3a1 1 0 011-1h2a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2H7a1 1 0 01-1-1zm4-8a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>',
                default: '<svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>'
            };
            
            return icons[extension] || icons.default;
        },

        formatFileSize(size) {
            if (size < 1024) {
                return size + ' bytes';
            } else if (size < 1024 * 1024) {
                return (size / 1024).toFixed(1) + ' KB';
            } else {
                return (size / (1024 * 1024)).toFixed(1) + ' MB';
            }
        },
        
        isImage(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
        },
        
        getImageUrl(file) {
            return URL.createObjectURL(
                Array.from(this.files).find(f => f.name === file)
            );
        },
        
        hasErrors() {
            return Object.keys(this.fileErrors).length > 0;
        }
    }"
    @dragover.prevent="dragover = true"
    @dragleave.prevent="dragover = false"
    @drop.prevent="
        dragover = false;
        $event.dataTransfer.files.length && (files = $event.dataTransfer.files);
        $refs.input.files = $event.dataTransfer.files;
        
        // If using Livewire, dispatch file change
        @if($isLivewireAttachment)
            const event = new Event('input', {
                bubbles: true,
                cancelable: true,
            });
            $refs.input.dispatchEvent(event);
        @endif
    "
    class="w-full"
>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div 
        class="relative border-2 border-dashed {{ $hasError ? 'border-red-300' : 'border-gray-300' }} {{ $roundedClasses }} p-4 {{ $dropzoneHeight }} flex flex-col justify-center items-center text-center"
        :class="{ 'border-green-500 bg-green-50': dragover, 'opacity-50': disabled }"
    >
        <!-- Actual file input -->
        <input 
            type="file" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            @if($multiple) multiple @endif
            @if($accept) accept="{{ $accept }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            x-ref="input"
            @change="files = $event.target.files"
            @if(isset($attributes['wire:model']))
                wire:model="{{ $attributes['wire:model'] }}"
            @elseif(isset($attributes['wire:model.defer']))
                wire:model.defer="{{ $attributes['wire:model.defer'] }}"
            @elseif(isset($attributes['wire:model.lazy']))
                wire:model.lazy="{{ $attributes['wire:model.lazy'] }}"
            @endif
            {{ $attributes->except(['wire:model', 'wire:model.defer', 'wire:model.lazy']) }}
        >
        
        <!-- Upload icon and text -->
        <div class="space-y-2 text-center" x-show="filenames.length === 0">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="text-sm text-gray-500">{{ $placeholder }}</p>
            
            @if($accept || $maxFileSize || $maxFiles > 1)
                <p class="text-xs text-gray-500">
                    @if($accept)
                        Accepted: {{ $acceptedTypes }} 
                    @endif
                    
                    @if($maxFileSize)
                        @if($accept) | @endif
                        Max size: {{ $maxFileSize }}MB
                    @endif
                    
                    @if($multiple && $maxFiles > 1)
                        @if($accept || $maxFileSize) | @endif
                        Max files: {{ $maxFiles }}
                    @endif
                </p>
            @endif
        </div>
        
        <!-- File list preview with progress -->
        <div class="w-full" x-show="filenames.length > 0">
            <h4 class="text-sm font-medium text-gray-700 mb-2" x-text="filenames.length + ' file' + (filenames.length !== 1 ? 's' : '') + ' selected'"></h4>
            
            <!-- Progress bar -->
            <div class="w-full h-1 bg-gray-200 rounded-full mb-2" x-show="uploading && {{ $progressBar ? 'true' : 'false' }}">
                <div class="h-full bg-green-500 rounded-full" :style="`width: ${uploadProgress}%`"></div>
            </div>
            
            <!-- Error message for max files -->
            <div class="text-red-500 text-xs mb-2" x-show="fileErrors.max_files" x-text="fileErrors.max_files"></div>
            
            <!-- File list -->
            <ul class="divide-y divide-gray-200 max-h-40 overflow-auto text-left">
                <template x-for="(filename, index) in filenames" :key="index">
                    <li class="py-2 flex items-center justify-between">
                        <div class="flex items-center">
                            <!-- File icon or image preview -->
                            <div class="flex-shrink-0 mr-2">
                                <template x-if="isImage(filename) && {{ $preview ? 'true' : 'false' }}">
                                    <img :src="getImageUrl(filename)" class="h-10 w-10 object-cover rounded" />
                                </template>
                                <template x-if="!isImage(filename) || {{ $preview ? 'false' : 'true' }}">
                                    <div x-html="getFileIcon(filename)"></div>
                                </template>
                            </div>
                            
                            <!-- Filename and size -->
                            <div>
                                <p class="text-sm text-gray-700 truncate" x-text="filename"></p>
                                <template x-if="{{ $showFileDetails ? 'true' : 'false' }}">
                                    <p class="text-xs text-gray-500" x-text="formatFileSize(Array.from(files).find(f => f.name === filename)?.size || 0)"></p>
                                </template>
                                <div class="text-xs text-red-500" x-show="fileErrors[filename]" x-text="fileErrors[filename]"></div>
                            </div>
                        </div>
                        
                        <!-- Remove button -->
                        <button type="button" @click.prevent="removeFile(filename)" class="text-gray-400 hover:text-red-500">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>
    
    @if($helper && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 
<v-touch class="card"
    :class="{'pdf': selectedFileIs('pdf') || selectedFileIs('text')}"
    @swiperight="goToPrev()"
    @swipeleft="goToNext()">

    <div class="card-image">
        <template v-if="selectedFileIs('pdf') || selectedFileIs('text')">
            <object :data="selectedFile.path" :type="selectedFile.type" width="100%" height="100%">
                 <p v-if="selectedFileIs('pdf')">{{ trans('MediaManager::messages.pdf') }}</p>
            </object>
        </template>

        <template v-else>
            <a :href="selectedFile.path"
                target="_blank"
                rel="noreferrer noopener"
                class="image">
                <img :src="selectedFilePreview" :alt="selectedFile.name">
            </a>
        </template>
    </div>

    <div class="card-content">
        <div class="media">
            <div class="media-content">
                {{-- name --}}
                <p class="title is-marginless">
                    <span class="link"
                        @click="copyLink(selectedFile.path)"
                        :title="linkCopied ? '{{ trans('MediaManager::messages.copied') }}' : '{{ trans('MediaManager::messages.copy_to_cp') }}'"
                        v-tippy="{arrow: true, hideOnClick: false, followCursor: true}"
                        @hidden="linkCopied = false">
                        @{{ selectedFile.name }}
                    </span>

                    {{-- pdf open --}}
                    <a v-if="selectedFileIs('pdf')"
                        :href="selectedFile.path"
                        target="_blank"
                        rel="noreferrer noopener">
                        <icon name="search"></icon>
                    </a>
                </p>

                {{-- date --}}
                <p class="subtitle is-6 m-t-5">@{{ selectedFile.last_modified_formated }}</p>

                {{-- ops --}}
                <p>
                    {{-- lock / unlock --}}
                    <span v-if="$refs.lock"
                        class="icon is-large link"
                        :class="IsLocked(selectedFile) ? 'is-danger' : 'is-success'"
                        :title="IsLocked(selectedFile) ? '{{ trans('MediaManager::messages.unlock') }}': '{{ trans('MediaManager::messages.lock') }}'"
                        v-tippy="{arrow: true, hideOnClick: false}"
                        @click="$refs.lock.click()">
                        <icon :name="IsLocked(selectedFile) ? 'lock' : 'unlock'" scale="1.2"></icon>
                    </span>

                    {{-- visibility --}}
                    <span v-if="$refs.vis"
                        class="icon is-large link"
                        :class="IsVisible(selectedFile) ? 'is-success' : 'is-danger'"
                        title="{{ trans('MediaManager::messages.visibility_set') }}"
                        v-tippy
                        @click="$refs.vis.click()">
                        <icon :name="IsVisible(selectedFile) ? 'eye' : 'eye-slash'" scale="1.2"></icon>
                    </span>
                </p>
            </div>

            <div class="media-right has-text-centered">
                <div>
                    {{-- download --}}
                    <button class="button btn-plain"
                        @click.prevent="saveFile(selectedFile)"
                        v-tippy
                        title="{{ trans('MediaManager::messages.download_file') }}">
                        <span class="icon"><icon name="download" scale="4"></icon></span>
                    </button>

                    {{-- size --}}
                    <p>@{{ getFileSize(selectedFile.size) }}</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="card-footer">
        {{-- move --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || !checkForFolders || isLoading"
                @click="moveItem()">
                <span class="icon is-small"><icon name="share"></icon></span>
                <span>{{ trans('MediaManager::messages.move') }}</span>
            </button>
        </div>

        {{-- rename --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || isLoading"
                @click="renameItem()">
                <span class="icon is-small"><icon name="terminal"></icon></span>
                <span>{{ trans('MediaManager::messages.rename') }}</span>
            </button>
        </div>

        {{-- editor --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || !selectedFileIs('image') || isLoading"
                @click="imageEditorCard()">
                <span class="icon"><icon name="object-ungroup" scale="1.2"></icon></span>
                <span>{{ trans('MediaManager::messages.editor') }}</span>
            </button>
        </div>

        {{-- delete --}}
        <div class="card-footer-item">
            <button class="button btn-plain is-fullwidth"
                :disabled="item_ops() || isLoading"
                @click="deleteItem()">
                <span class="icon is-small"><icon name="trash" scale="1.2"></icon></span>
                <span>{{ trans('MediaManager::messages.delete') }}</span>
            </button>
        </div>
    </footer>
</v-touch>

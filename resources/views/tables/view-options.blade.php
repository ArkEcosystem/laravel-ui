<div class="items-center @if($showMobile ?? false) flex @else hidden md:flex @endif">
    <div
        :class="{
            'text-theme-danger-400 border-theme-danger-100': tableView === 'grid',
            'text-theme-info-300 border-white': tableView !== 'grid',
        }"
        class="py-2 px-3 cursor-pointer text-theme-info-300 border-b-3"
        @click="tableView = 'grid'"
    >
        <x-ark-icon name="grid" />
    </div>

    <div
        :class="{
            'text-theme-danger-400 border-theme-danger-100': tableView === 'list',
            'text-theme-info-300 border-white': tableView !== 'list',
        }"
        class="py-2 px-3 cursor-pointer text-theme-info-300 border-b-3"
        @click="tableView = 'list'"
    >
        <x-ark-icon name="list" />
    </div>
</div>

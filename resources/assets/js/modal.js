import {
    disableBodyScroll,
    enableBodyScroll,
    clearAllBodyScrollLocks,
} from "body-scroll-lock";

const Modal = {
    previousPaddingRight: undefined,
    previousNavPaddingRight: undefined,

    defaultSettings: {
        reserveScrollBarGap: true,
        reserveNavScrollBarGap: true,
    },

    onModalOpened(scrollable, settings = Modal.defaultSettings) {
        if (settings.reserveScrollBarGap) {
            this.reserveModalScrollBarGap(scrollable);
        }

        if (settings.reserveNavScrollBarGap) {
            this.reserveNavScrollBarGap(scrollable);
        }

        disableBodyScroll(scrollable, {
            reserveScrollBarGap: !!settings.reserveScrollBarGap,
        });

        scrollable.focus();
    },

    onModalClosed(scrollable, settings = Modal.defaultSettings) {
        if (settings.reserveScrollBarGap) {
            this.restoreModalScrollBarGap(scrollable);
        }

        if (settings.reserveNavScrollBarGap) {
            this.restoreNavScrollBarGap(scrollable);
        }

        enableBodyScroll(scrollable);

        if (!document.querySelectorAll("[data-modal]").length) {
            clearAllBodyScrollLocks();
        }
    },

    alpine(
        extraData = {},
        modalName = "",
        eventSettings = Modal.defaultSettings
    ) {
        return {
            name: modalName,
            shown: false,
            onBeforeHide: false,
            onBeforeShow: false,
            onHidden: false,
            onShown: false,
            options: null,
            init() {
                const scrollable = this.getScrollable();
                if (this.name) {
                    Livewire.on("openModal", (modalName, ...options) => {
                        if (this.name === modalName) {
                            this.show(options);
                        }
                    });

                    Livewire.on("closeModal", (modalName) => {
                        if (this.name === modalName) {
                            this.hide();
                        }
                    });
                }

                this.$watch("shown", (shown) => {
                    if (shown && typeof this.onBeforeShow === "function") {
                        this.onBeforeShow(this.options);
                    }

                    if (!shown && typeof this.onBeforeHide === "function") {
                        this.onBeforeHide(this.options);
                    }

                    this.$nextTick(() => {
                        if (shown) {
                            if (typeof this.onShown === "function") {
                                this.onShown(this.options);
                            }

                            Modal.onModalOpened(scrollable, eventSettings);
                        } else {
                            if (typeof this.onHidden === "function") {
                                this.onHidden(this.options);
                            }

                            Modal.onModalClosed(scrollable, eventSettings);
                        }
                    });
                });

                if (this.shown) {
                    Modal.onModalOpened(scrollable, eventSettings);
                }
            },
            hide() {
                this.options = null;

                this.shown = false;
            },
            show(options) {
                this.options = options;

                this.shown = true;
            },
            getScrollable() {
                const { modal } = this.$refs;
                return modal;
            },
            ...extraData,
        };
    },

    livewire(extraData = {}, eventSettings = Modal.defaultSettings) {
        return {
            init() {
                const scrollable = this.getScrollable();

                this.$wire.on("modalClosed", () => {
                    this.$nextTick(() => {
                        Modal.onModalClosed(scrollable, eventSettings);
                    });
                });

                Modal.onModalOpened(scrollable, eventSettings);
            },
            getScrollable() {
                const { modal } = this.$refs;
                return modal;
            },
            ...extraData,
        };
    },

    // Based on https://github.com/willmcpo/body-scroll-lock/blob/master/src/bodyScrollLock.js#L72
    reserveModalScrollBarGap(container) {
        if (this.previousPaddingRight === undefined) {
            const scrollBarGap =
                window.innerWidth - document.documentElement.clientWidth;

            if (scrollBarGap > 0) {
                const computedBodyPaddingRight = parseInt(
                    window
                        .getComputedStyle(container)
                        .getPropertyValue("padding-right"),
                    10
                );
                this.previousPaddingRight = container.style.paddingRight;
                container.style.paddingRight = `${
                    computedBodyPaddingRight + scrollBarGap
                }px`;
            }
        }
    },

    // Based on https://github.com/willmcpo/body-scroll-lock/blob/master/src/bodyScrollLock.js#L92
    restoreModalScrollBarGap(container) {
        if (this.previousPaddingRight !== undefined) {
            container.style.paddingRight = this.previousPaddingRight;
            this.previousPaddingRight = undefined;
        }
    },

    reserveNavScrollBarGap() {
        const navbar = document.querySelector("header nav");
        if (this.previousNavPaddingRight === undefined) {
            const scrollBarGap =
                window.innerWidth - document.documentElement.clientWidth;

            if (scrollBarGap > 0) {
                const computedBodyPaddingRight = parseInt(
                    window
                        .getComputedStyle(navbar)
                        .getPropertyValue("padding-right"),
                    10
                );
                this.previousNavPaddingRight = navbar.style.paddingRight;
                navbar.style.paddingRight = `${
                    computedBodyPaddingRight + scrollBarGap
                }px`;
            }
        }
    },

    restoreNavScrollBarGap() {
        const navbar = document.querySelector("header nav");
        if (this.previousNavPaddingRight !== undefined) {
            navbar.style.paddingRight = this.previousNavPaddingRight;
            this.previousNavPaddingRight = undefined;
        }
    },
};

export default Modal;

import {
    disableBodyScroll,
    enableBodyScroll,
    clearAllBodyScrollLocks,
} from "body-scroll-lock";

const onModalClosed = (modal) => {
    enableBodyScroll(modal);

    if (!document.querySelectorAll("[data-modal]").length) {
        clearAllBodyScrollLocks();
    }
};

const onModalOpened = (modal) => {
    disableBodyScroll(modal);

    modal.focus();
};

const Modal = {
    alpine(extraData = {}) {
        return {
            shown: false,
            onBeforeHide: false,
            onBeforeShow: false,
            onHidden: false,
            onShown: false,
            init() {
                const { modal } = this.$refs;

                this.$watch("shown", (shown) => {
                    if (typeof this.onBeforeShow === "function") {
                        this.onBeforeShow();
                    }

                    if (typeof this.onBeforeHide === "function") {
                        this.onBeforeHide();
                    }

                    this.$nextTick(() => {
                        if (shown) {
                            if (typeof this.onShown === "function") {
                                this.onShown();
                            }

                            onModalOpened(modal);
                        } else {
                            if (typeof this.onHidden === "function") {
                                this.onHidden();
                            }

                            onModalClosed(modal);
                        }
                    });
                });

                if (this.shown) {
                    onModalOpened(modal);
                }
            },
            hide() {
                this.shown = false;
            },
            show() {
                this.shown = true;
            },
            ...extraData,
        };
    },
    livewire(extraData = {}) {
        return {
            init() {
                const { modal } = this.$refs;

                this.$wire.on("modalClosed", () => {
                    this.$nextTick(() => {
                        onModalClosed(modal);
                    });
                });

                onModalOpened(modal);
            },
            ...extraData,
        };
    },
};

export default Modal;

$(function () {
    $("#modal-custom").iziModal({
        overlayClose: !1,
        overlayColor: "rgba(0, 0, 0, 0.6)"
    }), $("#modal-custom").on("click", "header a", function (t) {
        t.preventDefault();
        t = $(this).index();
        $(this).addClass("active").siblings("a").removeClass("active"), $(this).parents("div").find("section").eq(t).removeClass("hide").siblings("section").addClass("hide"), 0 === $(this).index() ? $("#modal-custom .iziModal-content .icon-close").css("background", "#ddd") : $("#modal-custom .iziModal-content .icon-close").attr("style", "")
    }), $("#modal-custom").on("click", ".submit", function (t) {
        t.preventDefault();
        var e = "wobble", i = $(this).closest(".iziModal");
        i.hasClass(e) || (i.addClass(e), setTimeout(function () {
            i.removeClass(e)
        }, 1500))
    })
}), function (i) {
    "function" == typeof define && define.amd ? define(["jquery"], i) : "object" == typeof module && module.exports ? module.exports = function (t, e) {
        return void 0 === e && (e = "undefined" != typeof window ? require("jquery") : require("jquery")(t)), i(e), e
    } : i(jQuery)
}(function (c) {
    function d(t) {
        return -1 < (t = t || navigator.userAgent).indexOf("MSIE ") || -1 < t.indexOf("Trident/")
    }

    function u(t) {
        return parseInt(String(t).split(/%|px|em|cm|vh|vw/)[0])
    }

    function l(t, e) {
        this.init(t, e)
    }

    var h = c(window), s = c(document), p = "iziModal", o = "closing", a = "closed", f = "opening", m = "opened",
        e = "destroyed", r = function () {
            var t, e = document.createElement("fakeelement"), i = {
                animation: "animationend",
                OAnimation: "oAnimationEnd",
                MozAnimation: "animationend",
                WebkitAnimation: "webkitAnimationEnd"
            };
            for (t in i) if (void 0 !== e.style[t]) return i[t]
        }(), g = !!/Mobi/.test(navigator.userAgent), v = 0;
    return l.prototype = {
        constructor: l, init: function (t, n) {
            var o = this;
            this.$element = c(t), void 0 !== this.$element[0].id && "" !== this.$element[0].id ? this.id = this.$element[0].id : (this.id = p + Math.floor(1e7 * Math.random() + 1), this.$element.attr("id", this.id)), this.classes = void 0 !== this.$element.attr("class") ? this.$element.attr("class") : "", this.content = this.$element.html(), this.state = a, this.options = n, this.width = 0, this.timer = null, this.timerTimeout = null, this.progressBar = null, this.isPaused = !1, this.isFullscreen = !1, this.headerHeight = 0, this.modalHeight = 0, this.$overlay = c('<div class="' + p + '-overlay" style="background-color:' + n.overlayColor + '"></div>'), this.$navigate = c('<div class="' + p + '-navigate"><div class="' + p + '-navigate-caption">Use</div><button class="' + p + '-navigate-prev"></button><button class="' + p + '-navigate-next"></button></div>'), this.group = {
                name: this.$element.attr("data-" + p + "-group"),
                index: null,
                ids: []
            }, this.$element.attr("aria-hidden", "true"), this.$element.attr("aria-labelledby", this.id), this.$element.attr("role", "dialog"), this.$element.hasClass("iziModal") || this.$element.addClass("iziModal"), void 0 === this.group.name && "" !== n.group && (this.group.name = n.group, this.$element.attr("data-" + p + "-group", n.group)), !0 === this.options.loop && this.$element.attr("data-" + p + "-loop", !0), c.each(this.options, function (t, e) {
                var i = o.$element.attr("data-" + p + "-" + t);
                try {
                    void 0 !== i && (n[t] = "" === i || "true" == i || "false" != i && ("function" == typeof e ? new Function(i) : i))
                } catch (t) {
                }
            }), !1 !== n.appendTo && this.$element.appendTo(n.appendTo), !0 === n.iframe ? (this.$element.html('<div class="' + p + '-wrap"><div class="' + p + '-content"><iframe class="' + p + '-iframe"></iframe>' + this.content + "</div></div>"), null !== n.iframeHeight && this.$element.find("." + p + "-iframe").css("height", n.iframeHeight)) : this.$element.html('<div class="' + p + '-wrap"><div class="' + p + '-content">' + this.content + "</div></div>"), this.$wrap = this.$element.find("." + p + "-wrap"), null === n.zindex || isNaN(parseInt(n.zindex)) || (this.$element.css("z-index", n.zindex), this.$navigate.css("z-index", n.zindex - 1), this.$overlay.css("z-index", n.zindex - 2)), "" !== n.radius && this.$element.css("border-radius", n.radius), "" !== n.padding && this.$element.find("." + p + "-content").css("padding", n.padding), "" !== n.theme && ("light" === n.theme ? this.$element.addClass(p + "-light") : this.$element.addClass(n.theme)), !0 === n.rtl && this.$element.addClass(p + "-rtl"), !0 === n.openFullscreen && (this.isFullscreen = !0, this.$element.addClass("isFullscreen")), this.createHeader(), this.recalcWidth(), this.recalcVerticalPos()
        }, createHeader: function () {
            this.$header = c('<div class="' + p + '-header"><h2 class="' + p + '-header-title">' + this.options.title + '</h2><p class="' + p + '-header-subtitle">' + this.options.subtitle + '</p><div class="' + p + '-header-buttons"></div></div>'), !0 === this.options.closeButton && this.$header.find("." + p + "-header-buttons").append('<a href="javascript:void(0)" class="' + p + "-button " + p + '-button-close" data-' + p + "-close></a>"), !0 === this.options.fullscreen && this.$header.find("." + p + "-header-buttons").append('<a href="javascript:void(0)" class="' + p + "-button " + p + '-button-fullscreen" data-' + p + "-fullscreen></a>"), !0 !== this.options.timeoutProgressbar || isNaN(parseInt(this.options.timeout)) || !1 === this.options.timeout || 0 === this.options.timeout || this.$header.prepend('<div class="' + p + '-progressbar"><div style="background-color:' + this.options.timeoutProgressbarColor + '"></div></div>'), "" === this.options.subtitle && this.$header.addClass(p + "-noSubtitle"), "" !== this.options.title && (null !== this.options.headerColor && (!0 === this.options.borderBottom && this.$element.css("border-bottom", "3px solid " + this.options.headerColor), this.$header.css("background", this.options.headerColor)), null === this.options.icon && null === this.options.iconText || (this.$header.prepend('<i class="' + p + '-header-icon"></i>'), null !== this.options.icon && this.$header.find("." + p + "-header-icon").addClass(this.options.icon).css("color", this.options.iconColor), null !== this.options.iconText && this.$header.find("." + p + "-header-icon").html(this.options.iconText)), this.$element.css("overflow", "hidden").prepend(this.$header))
        }, setGroup: function (t) {
            var i, n = this, e = this.group.name || t;
            this.group.ids = [], void 0 !== t && t !== this.group.name && (this.group.name = e = t, this.$element.attr("data-" + p + "-group", e)), void 0 !== e && "" !== e && (i = 0, c.each(c("." + p + "[data-" + p + "-group=" + e + "]"), function (t, e) {
                n.group.ids.push(c(this)[0].id), n.id == c(this)[0].id && (n.group.index = i), i++
            }))
        }, toggle: function () {
            this.state == m && this.close(), this.state == a && this.open()
        }, open: function (i) {
            function n() {
                o.state = m, o.$element.trigger(m), o.options.onOpened && "function" == typeof o.options.onOpened && o.options.onOpened(o)
            }

            var o = this;
            if (this.state == a) {
                if (o.$element.off("click", "[data-" + p + "-close]").on("click", "[data-" + p + "-close]", function (t) {
                    t.preventDefault();
                    t = c(t.currentTarget).attr("data-" + p + "-transitionOut");
                    void 0 !== t ? o.close({transition: t}) : o.close()
                }), o.$element.off("click", "[data-" + p + "-fullscreen]").on("click", "[data-" + p + "-fullscreen]", function (t) {
                    t.preventDefault(), !0 === o.isFullscreen ? (o.isFullscreen = !1, o.$element.removeClass("isFullscreen")) : (o.isFullscreen = !0, o.$element.addClass("isFullscreen")), o.options.onFullscreen && "function" == typeof o.options.onFullscreen && o.options.onFullscreen(o), o.$element.trigger("fullscreen", o)
                }), o.$navigate.off("click", "." + p + "-navigate-next").on("click", "." + p + "-navigate-next", function (t) {
                    o.next(t)
                }), o.$element.off("click", "[data-" + p + "-next]").on("click", "[data-" + p + "-next]", function (t) {
                    o.next(t)
                }), o.$navigate.off("click", "." + p + "-navigate-prev").on("click", "." + p + "-navigate-prev", function (t) {
                    o.prev(t)
                }), o.$element.off("click", "[data-" + p + "-prev]").on("click", "[data-" + p + "-prev]", function (t) {
                    o.prev(t)
                }), this.setGroup(), this.state = f, this.$element.trigger(f), this.$element.attr("aria-hidden", "false"), !0 === this.options.iframe) {
                    this.$element.find("." + p + "-content").addClass(p + "-content-loader"), this.$element.find("." + p + "-iframe").on("load", function () {
                        c(this).parent().removeClass(p + "-content-loader")
                    });
                    var t = null;
                    try {
                        t = "" !== c(i.currentTarget).attr("href") ? c(i.currentTarget).attr("href") : null
                    } catch (t) {
                    }
                    if (null == (t = null !== this.options.iframeURL && null == t ? this.options.iframeURL : t)) throw new Error("Failed to find iframe URL");
                    this.$element.find("." + p + "-iframe").attr("src", t)
                }
                (this.options.bodyOverflow || g) && (c("html").addClass(p + "-isOverflow"), g && c("body").css("overflow", "hidden")), this.options.onOpening && "function" == typeof this.options.onOpening && this.options.onOpening(this), function () {
                    var t;
                    1 < o.group.ids.length && (o.$navigate.appendTo("body"), o.$navigate.addClass(o.options.transitionInOverlay), !0 === o.options.navigateCaption && o.$navigate.find("." + p + "-navigate-caption").show(), t = o.$element.outerWidth(), !1 !== o.options.navigateArrows ? "closeScreenEdge" === o.options.navigateArrows ? (o.$navigate.find("." + p + "-navigate-prev").css("left", 0).show(), o.$navigate.find("." + p + "-navigate-next").css("right", 0).show()) : (o.$navigate.find("." + p + "-navigate-prev").css("margin-left", -(t / 2 + 84)).show(), o.$navigate.find("." + p + "-navigate-next").css("margin-right", -(t / 2 + 84)).show()) : (o.$navigate.find("." + p + "-navigate-prev").hide(), o.$navigate.find("." + p + "-navigate-next").hide()), 0 !== o.group.index || 0 === c("." + p + "[data-" + p + '-group="' + o.group.name + '"][data-' + p + "-loop]").length && !1 === o.options.loop && o.$navigate.find("." + p + "-navigate-prev").hide(), o.group.index + 1 !== o.group.ids.length || 0 === c("." + p + "[data-" + p + '-group="' + o.group.name + '"][data-' + p + "-loop]").length && !1 === o.options.loop && o.$navigate.find("." + p + "-navigate-next").hide()), !0 === o.options.overlay ? o.$overlay.appendTo("body") : 0 < c(o.options.overlay).length && o.$overlay.appendTo(c(o.options.overlay)), o.options.transitionInOverlay && o.$overlay.addClass(o.options.transitionInOverlay);
                    var e = o.options.transitionIn;
                    "object" == typeof i && (void 0 === i.transition && void 0 === i.transitionIn || (e = i.transition || i.transitionIn)), "" !== e ? (o.$element.addClass("transitionIn " + e).show(), o.$wrap.one(r, function () {
                        o.$element.removeClass(e + " transitionIn"), o.$overlay.removeClass(o.options.transitionInOverlay), o.$navigate.removeClass(o.options.transitionInOverlay), n()
                    })) : (o.$element.show(), n()), !0 !== o.options.pauseOnHover || !0 !== o.options.pauseOnHover || !1 === o.options.timeout || isNaN(parseInt(o.options.timeout)) || !1 === o.options.timeout || 0 === o.options.timeout || (o.$element.off("mouseenter").on("mouseenter", function (t) {
                        t.preventDefault(), o.isPaused = !0
                    }), o.$element.off("mouseleave").on("mouseleave", function (t) {
                        t.preventDefault(), o.isPaused = !1
                    }))
                }(), !1 === this.options.timeout || isNaN(parseInt(this.options.timeout)) || !1 === this.options.timeout || 0 === this.options.timeout || (!0 === this.options.timeoutProgressbar ? (this.progressBar = {
                    hideEta: null,
                    maxHideTime: null,
                    currentTime: (new Date).getTime(),
                    el: this.$element.find("." + p + "-progressbar > div"),
                    updateProgress: function () {
                        var t;
                        o.isPaused || (o.progressBar.currentTime = o.progressBar.currentTime + 10, t = (o.progressBar.hideEta - o.progressBar.currentTime) / o.progressBar.maxHideTime * 100, o.progressBar.el.width(t + "%"), t < 0 && o.close())
                    }
                }, 0 < this.options.timeout && (this.progressBar.maxHideTime = parseFloat(this.options.timeout), this.progressBar.hideEta = (new Date).getTime() + this.progressBar.maxHideTime, this.timerTimeout = setInterval(this.progressBar.updateProgress, 10))) : this.timerTimeout = setTimeout(function () {
                    o.close()
                }, o.options.timeout)), this.options.overlayClose && !this.$element.hasClass(this.options.transitionOut) && this.$overlay.click(function () {
                    o.close()
                }), this.options.focusInput && this.$element.find(":input:not(button):enabled:visible:first").focus(), function t() {
                    o.recalcLayout(), o.timer = setTimeout(t, 100)
                }(), o.options.history && (t = document.title, document.title = t + " - " + o.options.title, document.location.hash = o.id, document.title = t), s.on("keydown." + p, function (t) {
                    o.options.closeOnEscape && 27 === t.keyCode && o.close()
                })
            }
        }, close: function (t) {
            function e() {
                n.state = a, n.$element.trigger(a), !0 === n.options.iframe && n.$element.find("." + p + "-iframe").attr("src", ""), (n.options.bodyOverflow || g) && (c("html").removeClass(p + "-isOverflow"), g && c("body").css("overflow", "auto")), n.options.onClosed && "function" == typeof n.options.onClosed && n.options.onClosed(n), !0 === n.options.restoreDefaultContent && n.$element.find("." + p + "-content").html(n.content), 0 === c("." + p + ":visible").length && c("html").removeClass(p + "-isAttached")
            }

            var i, n = this;
            this.state != m && this.state != f || (s.off("keydown." + p), this.state = o, this.$element.trigger(o), this.$element.attr("aria-hidden", "true"), clearTimeout(this.timer), clearTimeout(this.timerTimeout), n.options.onClosing && "function" == typeof n.options.onClosing && n.options.onClosing(this), i = this.options.transitionOut, "object" == typeof t && (void 0 === t.transition && void 0 === t.transitionOut || (i = t.transition || t.transitionOut)), "" !== i ? (this.$element.attr("class", [this.classes, p, i, "light" == this.options.theme ? p + "-light" : this.options.theme, !0 === this.isFullscreen ? "isFullscreen" : "", this.options.rtl ? p + "-rtl" : ""].join(" ")), this.$overlay.attr("class", p + "-overlay " + this.options.transitionOutOverlay), !1 !== n.options.navigateArrows && this.$navigate.attr("class", p + "-navigate " + this.options.transitionOutOverlay), this.$element.one(r, function () {
                n.$element.hasClass(i) && n.$element.removeClass(i + " transitionOut").hide(), n.$overlay.removeClass(n.options.transitionOutOverlay).remove(), n.$navigate.removeClass(n.options.transitionOutOverlay).remove(), e()
            })) : (this.$element.hide(), this.$overlay.remove(), this.$navigate.remove(), e()))
        }, next: function (t) {
            var n = this, o = "fadeInRight", e = "fadeOutLeft", i = c("." + p + ":visible"), s = {};
            s.out = this, void 0 !== t && "object" != typeof t ? (t.preventDefault(), i = c(t.currentTarget), o = i.attr("data-" + p + "-transitionIn"), e = i.attr("data-" + p + "-transitionOut")) : void 0 !== t && (void 0 !== t.transitionIn && (o = t.transitionIn), void 0 !== t.transitionOut && (e = t.transitionOut)), this.close({transition: e}), setTimeout(function () {
                for (var t = c("." + p + "[data-" + p + '-group="' + n.group.name + '"][data-' + p + "-loop]").length, e = n.group.index + 1; e <= n.group.ids.length; e++) {
                    try {
                        s.in = c("#" + n.group.ids[e]).data().iziModal
                    } catch (t) {
                    }
                    if (void 0 !== s.in) {
                        c("#" + n.group.ids[e]).iziModal("open", {transition: o});
                        break
                    }
                    if (e == n.group.ids.length && 0 < t || !0 === n.options.loop) for (var i = 0; i <= n.group.ids.length; i++) if (s.in = c("#" + n.group.ids[i]).data().iziModal, void 0 !== s.in) {
                        c("#" + n.group.ids[i]).iziModal("open", {transition: o});
                        break
                    }
                }
            }, 200), c(document).trigger(p + "-group-change", s)
        }, prev: function (t) {
            var n = this, o = "fadeInLeft", e = "fadeOutRight", i = c("." + p + ":visible"), s = {};
            s.out = this, void 0 !== t && "object" != typeof t ? (t.preventDefault(), i = c(t.currentTarget), o = i.attr("data-" + p + "-transitionIn"), e = i.attr("data-" + p + "-transitionOut")) : void 0 !== t && (void 0 !== t.transitionIn && (o = t.transitionIn), void 0 !== t.transitionOut && (e = t.transitionOut)), this.close({transition: e}), setTimeout(function () {
                for (var t = c("." + p + "[data-" + p + '-group="' + n.group.name + '"][data-' + p + "-loop]").length, e = n.group.index; 0 <= e; e--) {
                    try {
                        s.in = c("#" + n.group.ids[e - 1]).data().iziModal
                    } catch (t) {
                    }
                    if (void 0 !== s.in) {
                        c("#" + n.group.ids[e - 1]).iziModal("open", {transition: o});
                        break
                    }
                    if (0 === e && 0 < t || !0 === n.options.loop) for (var i = n.group.ids.length - 1; 0 <= i; i--) if (s.in = c("#" + n.group.ids[i]).data().iziModal, void 0 !== s.in) {
                        c("#" + n.group.ids[i]).iziModal("open", {transition: o});
                        break
                    }
                }
            }, 200), c(document).trigger(p + "-group-change", s)
        }, destroy: function () {
            var t = c.Event("destroy");
            this.$element.trigger(t), s.off("keydown." + p), clearTimeout(this.timer), clearTimeout(this.timerTimeout), !0 === this.options.iframe && this.$element.find("." + p + "-iframe").remove(), this.$element.html(this.$element.find("." + p + "-content").html()), this.$element.off("click", "[data-" + p + "-close]"), this.$element.off("click", "[data-" + p + "-fullscreen]"), this.$element.off("." + p).removeData(p).attr("style", ""), this.$overlay.remove(), this.$navigate.remove(), this.$element.trigger(e), this.$element = null
        }, getState: function () {
            return this.state
        }, getGroup: function () {
            return this.group
        }, setWidth: function (t) {
            this.options.width = t, this.recalcWidth();
            t = this.$element.outerWidth();
            !0 !== this.options.navigateArrows && "closeToModal" != this.options.navigateArrows || (this.$navigate.find("." + p + "-navigate-prev").css("margin-left", -(t / 2 + 84)).show(), this.$navigate.find("." + p + "-navigate-next").css("margin-right", -(t / 2 + 84)).show())
        }, setTop: function (t) {
            this.options.top = t, this.recalcVerticalPos(!1)
        }, setBottom: function (t) {
            this.options.bottom = t, this.recalcVerticalPos(!1)
        }, setHeader: function (t) {
            t ? this.$element.find("." + p + "-header").show() : (this.headerHeight = 0, this.$element.find("." + p + "-header").hide())
        }, setTitle: function (t) {
            this.options.title = t, 0 === this.headerHeight && this.createHeader(), 0 === this.$header.find("." + p + "-header-title").length && this.$header.append('<h2 class="' + p + '-header-title"></h2>'), this.$header.find("." + p + "-header-title").html(t)
        }, setSubtitle: function (t) {
            "" === t ? (this.$header.find("." + p + "-header-subtitle").remove(), this.$header.addClass(p + "-noSubtitle")) : (0 === this.$header.find("." + p + "-header-subtitle").length && this.$header.append('<p class="' + p + '-header-subtitle"></p>'), this.$header.removeClass(p + "-noSubtitle")), this.$header.find("." + p + "-header-subtitle").html(t), this.options.subtitle = t
        }, setIcon: function (t) {
            0 === this.$header.find("." + p + "-header-icon").length && this.$header.prepend('<i class="' + p + '-header-icon"></i>'), this.$header.find("." + p + "-header-icon").attr("class", p + "-header-icon " + t), this.options.icon = t
        }, setIconText: function (t) {
            this.$header.find("." + p + "-header-icon").html(t), this.options.iconText = t
        }, setHeaderColor: function (t) {
            !0 === this.options.borderBottom && this.$element.css("border-bottom", "3px solid " + t), this.$header.css("background", t), this.options.headerColor = t
        }, setZindex: function (t) {
            isNaN(parseInt(this.options.zindex)) || (this.options.zindex = t, this.$element.css("z-index", t), this.$navigate.css("z-index", t - 1), this.$overlay.css("z-index", t - 2))
        }, setFullscreen: function (t) {
            t ? (this.isFullscreen = !0, this.$element.addClass("isFullscreen")) : (this.isFullscreen = !1, this.$element.removeClass("isFullscreen"))
        }, setTransitionIn: function (t) {
            this.options.transitionIn = t
        }, setTransitionOut: function (t) {
            this.options.transitionOut = t
        }, startLoading: function () {
            this.$element.find("." + p + "-loader").length || this.$element.append('<div class="' + p + '-loader fadeIn"></div>'), this.$element.find("." + p + "-loader").css({
                top: this.headerHeight,
                borderRadius: this.options.radius
            })
        }, stopLoading: function () {
            var t = this.$element.find("." + p + "-loader");
            t.length || (this.$element.prepend('<div class="' + p + '-loader fadeIn"></div>'), t = this.$element.find("." + p + "-loader").css("border-radius", this.options.radius)), t.removeClass("fadeIn").addClass("fadeOut"), setTimeout(function () {
                t.remove()
            }, 600)
        }, recalcWidth: function () {
            var t;
            this.$element.css("max-width", this.options.width), d() && (1 < (t = this.options.width).toString().split("%").length && (t = this.$element.outerWidth()), this.$element.css({
                left: "50%",
                marginLeft: -t / 2
            }))
        }, recalcVerticalPos: function (t) {
            null !== this.options.top && !1 !== this.options.top ? (this.$element.css("margin-top", this.options.top), 0 === this.options.top && this.$element.css({
                borderTopRightRadius: 0,
                borderTopLeftRadius: 0
            })) : !1 === t && this.$element.css({
                marginTop: "",
                borderRadius: this.options.radius
            }), null !== this.options.bottom && !1 !== this.options.bottom ? (this.$element.css("margin-bottom", this.options.bottom), 0 === this.options.bottom && this.$element.css({
                borderBottomRightRadius: 0,
                borderBottomLeftRadius: 0
            })) : !1 === t && this.$element.css({marginBottom: "", borderRadius: this.options.radius})
        }, recalcLayout: function () {
            var t = this, e = h.height(), i = this.$element.outerHeight(), n = this.$element.outerWidth(),
                o = this.$element.find("." + p + "-content")[0].scrollHeight, s = o + this.headerHeight,
                a = this.$element.innerHeight() - this.headerHeight,
                r = (parseInt(-(this.$element.innerHeight() + 1) / 2), this.$wrap.scrollTop()), l = 0;
            d() && (n >= h.width() || !0 === this.isFullscreen ? this.$element.css({
                left: "",
                marginLeft: ""
            }) : this.$element.css({
                left: "50%",
                marginLeft: -n / 2
            })), !0 === this.options.borderBottom && (l = 3), this.$element.find("." + p + "-header").length && this.$element.find("." + p + "-header").is(":visible") ? (this.headerHeight = parseInt(this.$element.find("." + p + "-header").innerHeight()), this.$element.css("overflow", "hidden")) : (this.headerHeight = 0, this.$element.css("overflow", "")), this.$element.find("." + p + "-loader").length && this.$element.find("." + p + "-loader").css("top", this.headerHeight), i !== this.modalHeight && (this.modalHeight = i, this.options.onResize && "function" == typeof this.options.onResize && this.options.onResize(this)), this.state != m && this.state != f || (!0 === this.options.iframe && (e < this.options.iframeHeight + this.headerHeight + l || !0 === this.isFullscreen ? this.$element.find("." + p + "-iframe").css("height", e - (this.headerHeight + l)) : this.$element.find("." + p + "-iframe").css("height", this.options.iframeHeight)), i == e ? this.$element.addClass("isAttached") : this.$element.removeClass("isAttached"), !1 === this.isFullscreen && this.$element.width() >= h.width() ? this.$element.find("." + p + "-button-fullscreen").hide() : this.$element.find("." + p + "-button-fullscreen").show(), this.recalcButtons(), (e = !1 === this.isFullscreen ? e - (u(this.options.top) || 0) - (u(this.options.bottom) || 0) : e) < s ? (0 < this.options.top && null === this.options.bottom && o < h.height() && this.$element.addClass("isAttachedBottom"), 0 < this.options.bottom && null === this.options.top && o < h.height() && this.$element.addClass("isAttachedTop"), c("html").addClass(p + "-isAttached"), this.$element.css("height", e)) : (this.$element.css("height", o + (this.headerHeight + l)), this.$element.removeClass("isAttachedTop isAttachedBottom"), c("html").removeClass(p + "-isAttached")), a < o && e < s ? (t.$element.addClass("hasScroll"), t.$wrap.css("height", i - (t.headerHeight + l))) : (t.$element.removeClass("hasScroll"), t.$wrap.css("height", "auto")), a + r < o - 30 ? t.$element.addClass("hasShadow") : t.$element.removeClass("hasShadow"))
        }, recalcButtons: function () {
            var t = this.$header.find("." + p + "-header-buttons").innerWidth() + 10;
            !0 === this.options.rtl ? this.$header.css("padding-left", t) : this.$header.css("padding-right", t)
        }
    }, h.off("hashchange." + p + " load." + p).on("hashchange." + p + " load." + p, function (t) {
        var n = document.location.hash;
        if (0 === v) if ("" !== n) {
            c.each(c("." + p), function (t, e) {
                var i = c(e).iziModal("getState");
                "opened" != i && "opening" != i || "#" + c(e)[0].id !== n && c(e).iziModal("close")
            });
            try {
                var e = c(n).data();
                void 0 !== e && ("load" === t.type ? !1 !== e.iziModal.options.autoOpen && c(n).iziModal("open") : setTimeout(function () {
                    c(n).iziModal("open")
                }, 200))
            } catch (t) {
            }
        } else c.each(c("." + p), function (t, e) {
            var i;
            void 0 !== c(e).data().iziModal && ("opened" != (i = c(e).iziModal("getState")) && "opening" != i || c(e).iziModal("close"))
        }); else v = 0
    }), s.off("click", "[data-" + p + "-open]").on("click", "[data-" + p + "-open]", function (t) {
        t.preventDefault();
        var e = c("." + p + ":visible"), i = c(t.currentTarget).attr("data-" + p + "-open"),
            n = c(t.currentTarget).attr("data-" + p + "-transitionIn"),
            t = c(t.currentTarget).attr("data-" + p + "-transitionOut");
        void 0 !== t ? e.iziModal("close", {transition: t}) : e.iziModal("close"), setTimeout(function () {
            void 0 !== n ? c(i).iziModal("open", {transition: n}) : c(i).iziModal("open")
        }, 200)
    }), s.off("keyup." + p).on("keyup." + p, function (t) {
        var e, i, n;
        c("." + p + ":visible").length && (e = c("." + p + ":visible")[0].id, i = c("#" + e).iziModal("getGroup"), t = (n = t || window.event).target || n.srcElement, void 0 === e || void 0 === i.name || n.ctrlKey || n.metaKey || n.altKey || "INPUT" === t.tagName.toUpperCase() || "TEXTAREA" == t.tagName.toUpperCase() || (37 === n.keyCode ? c("#" + e).iziModal("prev", n) : 39 === n.keyCode && c("#" + e).iziModal("next", n)))
    }), c.fn[p] = function (t, e) {
        if (!c(this).length && "object" == typeof t) {
            var i = {$el: document.createElement("div"), id: this.selector.split("#"), class: this.selector.split(".")};
            if (1 < i.id.length) {
                try {
                    i.$el = document.createElement(id[0])
                } catch (t) {
                }
                i.$el.id = this.selector.split("#")[1].trim()
            } else if (1 < i.class.length) {
                try {
                    i.$el = document.createElement(i.class[0])
                } catch (t) {
                }
                for (var n = 1; n < i.class.length; n++) i.$el.classList.add(i.class[n].trim())
            }
            document.body.appendChild(i.$el), this.push(c(this.selector))
        }
        for (var o = 0; o < this.length; o++) {
            var s = c(this[o]), a = s.data(p), r = c.extend({}, c.fn[p].defaults, s.data(), "object" == typeof t && t);
            if (a || t && "object" != typeof t) {
                if ("string" == typeof t && void 0 !== a) return a[t].apply(a, [].concat(e))
            } else s.data(p, a = new l(s, r));
            r.autoOpen && (isNaN(parseInt(r.autoOpen)) ? !0 === r.autoOpen && a.open() : setTimeout(function () {
                a.open()
            }, r.autoOpen), v++)
        }
        return this
    }, c.fn[p].defaults = {
        title: "",
        subtitle: "",
        headerColor: "#88A0B9",
        theme: "",
        appendTo: ".body",
        icon: null,
        iconText: null,
        iconColor: "",
        rtl: !1,
        width: 600,
        top: null,
        bottom: null,
        borderBottom: !0,
        padding: 0,
        radius: 3,
        zindex: 999,
        iframe: !1,
        iframeHeight: 400,
        iframeURL: null,
        focusInput: !0,
        group: "",
        loop: !1,
        navigateCaption: !0,
        navigateArrows: !0,
        history: !1,
        restoreDefaultContent: !1,
        autoOpen: 0,
        bodyOverflow: !1,
        fullscreen: !1,
        openFullscreen: !1,
        closeOnEscape: !0,
        closeButton: !0,
        overlay: !0,
        overlayClose: !0,
        overlayColor: "rgba(0, 0, 0, 0.4)",
        timeout: !1,
        timeoutProgressbar: !1,
        pauseOnHover: !1,
        timeoutProgressbarColor: "rgba(255,255,255,0.5)",
        transitionIn: "comingIn",
        transitionOut: "comingOut",
        transitionInOverlay: "fadeIn",
        transitionOutOverlay: "fadeOut",
        onFullscreen: function () {
        },
        onResize: function () {
        },
        onOpening: function () {
        },
        onOpened: function () {
        },
        onClosing: function () {
        },
        onClosed: function () {
        }
    }, c.fn[p].Constructor = l, c.fn.iziModal
}), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : t.Popper = e()
}(this, function () {
    "use strict";

    function o(t) {
        return t && "[object Function]" === {}.toString.call(t)
    }

    function h(t, e) {
        if (1 !== t.nodeType) return [];
        t = getComputedStyle(t, null);
        return e ? t[e] : t
    }

    function p(t) {
        return "HTML" === t.nodeName ? t : t.parentNode || t.host
    }

    function f(t) {
        if (!t) return document.body;
        switch (t.nodeName) {
            case"HTML":
            case"BODY":
                return t.ownerDocument.body;
            case"#document":
                return t.body
        }
        var e = h(t), i = e.overflow, n = e.overflowX, e = e.overflowY;
        return /(auto|scroll)/.test(i + e + n) ? t : f(p(t))
    }

    function c(t) {
        var e = t && t.offsetParent, i = e && e.nodeName;
        return i && "BODY" !== i && "HTML" !== i ? -1 !== ["TD", "TABLE"].indexOf(e.nodeName) && "static" === h(e, "position") ? c(e) : e : (t ? t.ownerDocument : document).documentElement
    }

    function s(t) {
        return null === t.parentNode ? t : s(t.parentNode)
    }

    function m(t, e) {
        if (!(t && t.nodeType && e && e.nodeType)) return document.documentElement;
        var i = t.compareDocumentPosition(e) & Node.DOCUMENT_POSITION_FOLLOWING, n = i ? t : e, o = i ? e : t,
            i = document.createRange();
        i.setStart(n, 0), i.setEnd(o, 0);
        i = i.commonAncestorContainer;
        if (t !== i && e !== i || n.contains(o)) return "BODY" === (o = (n = i).nodeName) || "HTML" !== o && c(n.firstElementChild) !== n ? c(i) : i;
        i = s(t);
        return i.host ? m(i.host, e) : m(t, s(e).host)
    }

    function g(t, e) {
        var i = "top" === (1 < arguments.length && void 0 !== e ? e : "top") ? "scrollTop" : "scrollLeft",
            e = t.nodeName;
        if ("BODY" !== e && "HTML" !== e) return t[i];
        e = t.ownerDocument.documentElement;
        return (t.ownerDocument.scrollingElement || e)[i]
    }

    function a(t, e) {
        var i = "x" === e ? "Left" : "Top", e = "Left" == i ? "Right" : "Bottom";
        return parseFloat(t["border" + i + "Width"], 10) + parseFloat(t["border" + e + "Width"], 10)
    }

    function n(t, e, i, n) {
        return D(e["offset" + t], e["scroll" + t], i["client" + t], i["offset" + t], i["scroll" + t], W() ? i["offset" + t] + n["margin" + ("Height" === t ? "Top" : "Left")] + n["margin" + ("Height" === t ? "Bottom" : "Right")] : 0)
    }

    function v() {
        var t = document.body, e = document.documentElement, i = W() && getComputedStyle(e);
        return {height: n("Height", t, e, i), width: n("Width", t, e, i)}
    }

    function b(t) {
        return q({}, t, {right: t.left + t.width, bottom: t.top + t.height})
    }

    function d(t) {
        var e = {};
        if (W()) try {
            var e = t.getBoundingClientRect(), i = g(t, "top"), n = g(t, "left");
            e.top += i, e.left += n, e.bottom += i, e.right += n
        } catch (t) {
        } else e = t.getBoundingClientRect();
        var o = {left: e.left, top: e.top, width: e.right - e.left, height: e.bottom - e.top},
            i = "HTML" === t.nodeName ? v() : {}, n = i.width || t.clientWidth || o.right - o.left,
            e = i.height || t.clientHeight || o.bottom - o.top, i = t.offsetWidth - n, n = t.offsetHeight - e;
        return (i || n) && (i -= a(e = h(t), "x"), n -= a(e, "y"), o.width -= i, o.height -= n), b(o)
    }

    function y(t, e) {
        var i = W(), n = "HTML" === e.nodeName, o = d(t), s = d(e), a = f(t), r = h(e),
            l = parseFloat(r.borderTopWidth, 10), t = parseFloat(r.borderLeftWidth, 10),
            o = b({top: o.top - s.top - l, left: o.left - s.left - t, width: o.width, height: o.height});
        return o.marginTop = 0, o.marginLeft = 0, !i && n && (n = parseFloat(r.marginTop, 10), r = parseFloat(r.marginLeft, 10), o.top -= l - n, o.bottom -= l - n, o.left -= t - r, o.right -= t - r, o.marginTop = n, o.marginLeft = r), o = (i ? e.contains(a) : e === a && "BODY" !== a.nodeName) ? function (t, e, i) {
            var n = 2 < arguments.length && void 0 !== i && i, i = g(e, "top"), e = g(e, "left"), n = n ? -1 : 1;
            return t.top += i * n, t.bottom += i * n, t.left += e * n, t.right += e * n, t
        }(o, e) : o
    }

    function r(t, e, i, n) {
        var o, s, a, r, l, c, d = {top: 0, left: 0}, u = m(t, e);
        return "viewport" === n ? (a = (s = u).ownerDocument.documentElement, r = y(s, a), l = D(a.clientWidth, window.innerWidth || 0), c = D(a.clientHeight, window.innerHeight || 0), s = g(a), a = g(a, "left"), d = b({
            top: s - r.top + r.marginTop,
            left: a - r.left + r.marginLeft,
            width: l,
            height: c
        })) : ("scrollParent" === n ? "BODY" === (o = f(p(e))).nodeName && (o = t.ownerDocument.documentElement) : o = "window" === n ? t.ownerDocument.documentElement : n, n = y(o, u), "HTML" !== o.nodeName || function t(e) {
            var i = e.nodeName;
            return "BODY" !== i && "HTML" !== i && ("fixed" === h(e, "position") || t(p(e)))
        }(u) ? d = n : (u = (o = v()).height, o = o.width, d.top += n.top - n.marginTop, d.bottom = u + n.top, d.left += n.left - n.marginLeft, d.right = o + n.left)), d.left += i, d.top += i, d.right -= i, d.bottom -= i, d
    }

    function l(t, e, i, n, o, s) {
        s = 5 < arguments.length && void 0 !== s ? s : 0;
        if (-1 === t.indexOf("auto")) return t;
        var o = r(i, n, s, o), a = {
            top: {width: o.width, height: e.top - o.top},
            right: {width: o.right - e.right, height: o.height},
            bottom: {width: o.width, height: o.bottom - e.bottom},
            left: {width: e.left - o.left, height: o.height}
        }, e = Object.keys(a).map(function (t) {
            return q({key: t}, a[t], {area: (t = a[t]).width * t.height})
        }).sort(function (t, e) {
            return e.area - t.area
        }), o = e.filter(function (t) {
            var e = t.width, t = t.height;
            return e >= i.clientWidth && t >= i.clientHeight
        }), e = (0 < o.length ? o : e)[0].key, t = t.split("-")[1];
        return e + (t ? "-" + t : "")
    }

    function u(t, e, i) {
        return y(i, m(e, i))
    }

    function w(t) {
        var e = getComputedStyle(t), i = parseFloat(e.marginTop) + parseFloat(e.marginBottom),
            e = parseFloat(e.marginLeft) + parseFloat(e.marginRight);
        return {width: t.offsetWidth + e, height: t.offsetHeight + i}
    }

    function x(t) {
        var e = {left: "right", right: "left", bottom: "top", top: "bottom"};
        return t.replace(/left|right|bottom|top/g, function (t) {
            return e[t]
        })
    }

    function _(t, e, i) {
        i = i.split("-")[0];
        var n = w(t), o = {width: n.width, height: n.height}, s = -1 !== ["right", "left"].indexOf(i),
            a = s ? "top" : "left", r = s ? "left" : "top", t = s ? "height" : "width", s = s ? "width" : "height";
        return o[a] = e[a] + e[t] / 2 - n[t] / 2, o[r] = i === r ? e[r] - n[s] : e[x(r)], o
    }

    function C(t, e) {
        return Array.prototype.find ? t.find(e) : t.filter(e)[0]
    }

    function S(t, i, e) {
        return (void 0 === e ? t : t.slice(0, function (t, e, i) {
            if (Array.prototype.findIndex) return t.findIndex(function (t) {
                return t[e] === i
            });
            var n = C(t, function (t) {
                return t[e] === i
            });
            return t.indexOf(n)
        }(t, "name", e))).forEach(function (t) {
            t.function && console.warn("`modifier.function` is deprecated, use `modifier.fn`!");
            var e = t.function || t.fn;
            t.enabled && o(e) && (i.offsets.popper = b(i.offsets.popper), i.offsets.reference = b(i.offsets.reference), i = e(i, t))
        }), i
    }

    function t(t, i) {
        return t.some(function (t) {
            var e = t.name;
            return t.enabled && e === i
        })
    }

    function T(t) {
        for (var e = [!1, "ms", "Webkit", "Moz", "O"], i = t.charAt(0).toUpperCase() + t.slice(1), n = 0; n < e.length - 1; n++) {
            var o = e[n], o = o ? "" + o + i : t;
            if (void 0 !== document.body.style[o]) return o
        }
        return null
    }

    function $(t) {
        t = t.ownerDocument;
        return t ? t.defaultView : window
    }

    function e(t, e, i, n) {
        i.updateBound = n, $(t).addEventListener("resize", i.updateBound, {passive: !0});
        t = f(t);
        return function t(e, i, n, o) {
            var s = "BODY" === e.nodeName, e = s ? e.ownerDocument.defaultView : e;
            e.addEventListener(i, n, {passive: !0}), s || t(f(e.parentNode), i, n, o), o.push(e)
        }(t, "scroll", i.updateBound, i.scrollParents), i.scrollElement = t, i.eventsEnabled = !0, i
    }

    function i() {
        var t, e;
        this.state.eventsEnabled && (cancelAnimationFrame(this.scheduleUpdate), this.state = (t = this.reference, e = this.state, $(t).removeEventListener("resize", e.updateBound), e.scrollParents.forEach(function (t) {
            t.removeEventListener("scroll", e.updateBound)
        }), e.updateBound = null, e.scrollParents = [], e.scrollElement = null, e.eventsEnabled = !1, e))
    }

    function E(t) {
        return "" !== t && !isNaN(parseFloat(t)) && isFinite(t)
    }

    function k(i, n) {
        Object.keys(n).forEach(function (t) {
            var e = "";
            -1 !== ["width", "height", "top", "right", "bottom", "left"].indexOf(t) && E(n[t]) && (e = "px"), i.style[t] = n[t] + e
        })
    }

    function I(t, e, i) {
        var n = C(t, function (t) {
            return t.name === e
        }), o = !!n && t.some(function (t) {
            return t.name === i && t.enabled && t.order < n.order
        });
        return o || (t = "`" + e + "`", console.warn("`" + i + "` modifier is required by " + t + " modifier in order to work, be sure to include it before " + t + "!")), o
    }

    function O(t, e) {
        e = 1 < arguments.length && void 0 !== e && e, t = U.indexOf(t), t = U.slice(t + 1).concat(U.slice(0, t));
        return e ? t.reverse() : t
    }

    function z(t, r, l, e) {
        var o = [0, 0], n = -1 !== ["right", "left"].indexOf(e), i = t.split(/(\+|\-)/).map(function (t) {
            return t.trim()
        }), e = i.indexOf(C(i, function (t) {
            return -1 !== t.search(/,|\s/)
        }));
        i[e] && -1 === i[e].indexOf(",") && console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");
        t = /\s*,\s*|\s+/;
        return (-1 === e ? [i] : [i.slice(0, e).concat([i[e].split(t)[0]]), [i[e].split(t)[1]].concat(i.slice(e + 1))]).map(function (t, e) {
            var a = (1 === e ? !n : n) ? "height" : "width", i = !1;
            return t.reduce(function (t, e) {
                return "" === t[t.length - 1] && -1 !== ["+", "-"].indexOf(e) ? (t[t.length - 1] = e, i = !0, t) : i ? (t[t.length - 1] += e, i = !1, t) : t.concat(e)
            }, []).map(function (t) {
                return i = a, n = r, o = l, t = +(s = (e = t).match(/((?:\-|\+)?\d*\.?\d*)(.*)/))[1], s = s[2], t ? 0 !== s.indexOf("%") ? "vh" !== s && "vw" !== s ? t : ("vh" === s ? D(document.documentElement.clientHeight, window.innerHeight || 0) : D(document.documentElement.clientWidth, window.innerWidth || 0)) / 100 * t : b(o = "%p" === s ? n : o)[i] / 100 * t : e;
                var e, i, n, o, s
            })
        }).forEach(function (i, n) {
            i.forEach(function (t, e) {
                E(t) && (o[n] += t * ("-" === i[e - 1] ? -1 : 1))
            })
        }), o
    }

    for (var A = Math.min, P = Math.floor, D = Math.max, L = "undefined" != typeof window && "undefined" != typeof document, M = ["Edge", "Trident", "Firefox"], N = 0, H = 0; H < M.length; H += 1) if (L && 0 <= navigator.userAgent.indexOf(M[H])) {
        N = 1;
        break
    }

    function j(t, e, i) {
        return e in t ? Object.defineProperty(t, e, {
            value: i,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : t[e] = i, t
    }

    var B, R = L && window.Promise ? function (t) {
            var e = !1;
            return function () {
                e || (e = !0, window.Promise.resolve().then(function () {
                    e = !1, t()
                }))
            }
        } : function (t) {
            var e = !1;
            return function () {
                e || (e = !0, setTimeout(function () {
                    e = !1, t()
                }, N))
            }
        }, W = function () {
            return B = null == B ? -1 !== navigator.appVersion.indexOf("MSIE 10") : B
        }, F = function (t, e, i) {
            return e && Z(t.prototype, e), i && Z(t, i), t
        }, q = Object.assign || function (t) {
            for (var e, i = 1; i < arguments.length; i++) for (var n in e = arguments[i]) Object.prototype.hasOwnProperty.call(e, n) && (t[n] = e[n]);
            return t
        },
        V = ["auto-start", "auto", "auto-end", "top-start", "top", "top-end", "right-start", "right", "right-end", "bottom-end", "bottom", "bottom-start", "left-end", "left", "left-start"],
        U = V.slice(3), G = "flip", Y = "clockwise", X = "counterclockwise", F = (F(K, [{
            key: "update", value: function () {
                return function () {
                    var t;
                    this.state.isDestroyed || ((t = {
                        instance: this,
                        styles: {},
                        arrowStyles: {},
                        attributes: {},
                        flipped: !1,
                        offsets: {}
                    }).offsets.reference = u(this.state, this.popper, this.reference), t.placement = l(this.options.placement, t.offsets.reference, this.popper, this.reference, this.options.modifiers.flip.boundariesElement, this.options.modifiers.flip.padding), t.originalPlacement = t.placement, t.offsets.popper = _(this.popper, t.offsets.reference, t.placement), t.offsets.popper.position = "absolute", t = S(this.modifiers, t), this.state.isCreated ? this.options.onUpdate(t) : (this.state.isCreated = !0, this.options.onCreate(t)))
                }.call(this)
            }
        }, {
            key: "destroy", value: function () {
                return function () {
                    return this.state.isDestroyed = !0, t(this.modifiers, "applyStyle") && (this.popper.removeAttribute("x-placement"), this.popper.style.left = "", this.popper.style.position = "", this.popper.style.top = "", this.popper.style[T("transform")] = ""), this.disableEventListeners(), this.options.removeOnDestroy && this.popper.parentNode.removeChild(this.popper), this
                }.call(this)
            }
        }, {
            key: "enableEventListeners", value: function () {
                return function () {
                    this.state.eventsEnabled || (this.state = e(this.reference, this.options, this.state, this.scheduleUpdate))
                }.call(this)
            }
        }, {
            key: "disableEventListeners", value: function () {
                return i.call(this)
            }
        }]), K);

    function K(t, e) {
        var i = this, n = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : {};
        (function (t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        })(this, K), this.scheduleUpdate = function () {
            return requestAnimationFrame(i.update)
        }, this.update = R(this.update.bind(this)), this.options = q({}, K.Defaults, n), this.state = {
            isDestroyed: !1,
            isCreated: !1,
            scrollParents: []
        }, this.reference = t && t.jquery ? t[0] : t, this.popper = e && e.jquery ? e[0] : e, this.options.modifiers = {}, Object.keys(q({}, K.Defaults.modifiers, n.modifiers)).forEach(function (t) {
            i.options.modifiers[t] = q({}, K.Defaults.modifiers[t] || {}, n.modifiers ? n.modifiers[t] : {})
        }), this.modifiers = Object.keys(this.options.modifiers).map(function (t) {
            return q({name: t}, i.options.modifiers[t])
        }).sort(function (t, e) {
            return t.order - e.order
        }), this.modifiers.forEach(function (t) {
            t.enabled && o(t.onLoad) && t.onLoad(i.reference, i.popper, i.options, t, i.state)
        }), this.update();
        e = this.options.eventsEnabled;
        e && this.enableEventListeners(), this.state.eventsEnabled = e
    }

    function Z(t, e) {
        for (var i, n = 0; n < e.length; n++) (i = e[n]).enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
    }

    return F.Utils = ("undefined" == typeof window ? global : window).PopperUtils, F.placements = V, F.Defaults = {
        placement: "bottom", eventsEnabled: !0, removeOnDestroy: !1, onCreate: function () {
        }, onUpdate: function () {
        }, modifiers: {
            shift: {
                order: 100, enabled: !0, fn: function (t) {
                    var e, i, n = t.placement, o = n.split("-")[0], s = n.split("-")[1];
                    return s && (e = (i = t.offsets).reference, n = i.popper, o = (i = -1 !== ["bottom", "top"].indexOf(o)) ? "width" : "height", o = {
                        start: j({}, i = i ? "left" : "top", e[i]),
                        end: j({}, i, e[i] + e[o] - n[o])
                    }, t.offsets.popper = q({}, n, o[s])), t
                }
            }, offset: {
                order: 200, enabled: !0, fn: function (t, e) {
                    var i = e.offset, n = t.placement, e = (o = t.offsets).popper, o = o.reference, n = n.split("-")[0],
                        o = E(+i) ? [+i, 0] : z(i, e, o, n);
                    return "left" === n ? (e.top += o[0], e.left -= o[1]) : "right" === n ? (e.top += o[0], e.left += o[1]) : "top" === n ? (e.left += o[0], e.top -= o[1]) : "bottom" === n && (e.left += o[0], e.top += o[1]), t.popper = e, t
                }, offset: 0
            }, preventOverflow: {
                order: 300, enabled: !0, fn: function (t, n) {
                    var e = n.boundariesElement || c(t.instance.popper);
                    t.instance.reference === e && (e = c(e));
                    var o = r(t.instance.popper, t.instance.reference, n.padding, e);
                    n.boundaries = o;
                    var e = n.priority, s = t.offsets.popper, i = {
                        primary: function (t) {
                            var e = s[t];
                            return s[t] < o[t] && !n.escapeWithReference && (e = D(s[t], o[t])), j({}, t, e)
                        }, secondary: function (t) {
                            var e = "right" === t ? "left" : "top", i = s[e];
                            return s[t] > o[t] && !n.escapeWithReference && (i = A(s[e], o[t] - ("right" === t ? s.width : s.height))), j({}, e, i)
                        }
                    };
                    return e.forEach(function (t) {
                        var e = -1 === ["left", "top"].indexOf(t) ? "secondary" : "primary";
                        s = q({}, s, i[e](t))
                    }), t.offsets.popper = s, t
                }, priority: ["left", "right", "top", "bottom"], padding: 5, boundariesElement: "scrollParent"
            }, keepTogether: {
                order: 400, enabled: !0, fn: function (t) {
                    var e = t.offsets, i = e.popper, n = e.reference, o = t.placement.split("-")[0], s = P,
                        a = -1 !== ["top", "bottom"].indexOf(o), e = a ? "right" : "bottom", o = a ? "left" : "top",
                        a = a ? "width" : "height";
                    return i[e] < s(n[o]) && (t.offsets.popper[o] = s(n[o]) - i[a]), i[o] > s(n[e]) && (t.offsets.popper[o] = s(n[e])), t
                }
            }, arrow: {
                order: 500, enabled: !0, fn: function (t, e) {
                    if (!I(t.instance.modifiers, "arrow", "keepTogether")) return t;
                    var i = e.element;
                    if ("string" == typeof i) {
                        if (!(i = t.instance.popper.querySelector(i))) return t
                    } else if (!t.instance.popper.contains(i)) return console.warn("WARNING: `arrow.element` must be child of its popper element!"), t;
                    var n = t.placement.split("-")[0], o = t.offsets, s = o.popper, a = o.reference,
                        r = -1 !== ["left", "right"].indexOf(n), l = r ? "height" : "width", c = r ? "Top" : "Left",
                        d = c.toLowerCase(), e = r ? "left" : "top", o = r ? "bottom" : "right", n = w(i)[l];
                    a[o] - n < s[d] && (t.offsets.popper[d] -= s[d] - (a[o] - n)), a[d] + n > s[o] && (t.offsets.popper[d] += a[d] + n - s[o]), t.offsets.popper = b(t.offsets.popper);
                    r = a[d] + a[l] / 2 - n / 2, o = h(t.instance.popper), a = parseFloat(o["margin" + c], 10), c = parseFloat(o["border" + c + "Width"], 10), c = r - t.offsets.popper[d] - a - c, c = D(A(s[l] - n, c), 0);
                    return t.arrowElement = i, t.offsets.arrow = (j(i = {}, d, Math.round(c)), j(i, e, ""), i), t
                }, element: "[x-arrow]"
            }, flip: {
                order: 600, enabled: !0, fn: function (l, c) {
                    if (t(l.instance.modifiers, "inner")) return l;
                    if (l.flipped && l.placement === l.originalPlacement) return l;
                    var d = r(l.instance.popper, l.instance.reference, c.padding, c.boundariesElement),
                        u = l.placement.split("-")[0], h = x(u), p = l.placement.split("-")[1] || "", f = [];
                    switch (c.behavior) {
                        case G:
                            f = [u, h];
                            break;
                        case Y:
                            f = O(u);
                            break;
                        case X:
                            f = O(u, !0);
                            break;
                        default:
                            f = c.behavior
                    }
                    return f.forEach(function (t, e) {
                        if (u !== t || f.length === e + 1) return l;
                        u = l.placement.split("-")[0], h = x(u);
                        var i = l.offsets.popper, n = l.offsets.reference, o = P,
                            s = "left" === u && o(i.right) > o(n.left) || "right" === u && o(i.left) < o(n.right) || "top" === u && o(i.bottom) > o(n.top) || "bottom" === u && o(i.top) < o(n.bottom),
                            a = o(i.left) < o(d.left), r = o(i.right) > o(d.right), t = o(i.top) < o(d.top),
                            n = o(i.bottom) > o(d.bottom),
                            i = "left" === u && a || "right" === u && r || "top" === u && t || "bottom" === u && n,
                            o = -1 !== ["top", "bottom"].indexOf(u),
                            n = !!c.flipVariations && (o && "start" === p && a || o && "end" === p && r || !o && "start" === p && t || !o && "end" === p && n);
                        (s || i || n) && (l.flipped = !0, (s || i) && (u = f[e + 1]), n && (p = "end" === (n = p) ? "start" : "start" === n ? "end" : n), l.placement = u + (p ? "-" + p : ""), l.offsets.popper = q({}, l.offsets.popper, _(l.instance.popper, l.offsets.reference, l.placement)), l = S(l.instance.modifiers, l, "flip"))
                    }), l
                }, behavior: "flip", padding: 5, boundariesElement: "viewport"
            }, inner: {
                order: 700, enabled: !1, fn: function (t) {
                    var e = t.placement, i = e.split("-")[0], n = t.offsets, o = n.popper, s = n.reference,
                        a = -1 !== ["left", "right"].indexOf(i), n = -1 === ["top", "left"].indexOf(i);
                    return o[a ? "left" : "top"] = s[i] - (n ? o[a ? "width" : "height"] : 0), t.placement = x(e), t.offsets.popper = b(o), t
                }
            }, hide: {
                order: 800, enabled: !0, fn: function (t) {
                    if (!I(t.instance.modifiers, "hide", "preventOverflow")) return t;
                    var e = t.offsets.reference, i = C(t.instance.modifiers, function (t) {
                        return "preventOverflow" === t.name
                    }).boundaries;
                    if (e.bottom < i.top || e.left > i.right || e.top > i.bottom || e.right < i.left) {
                        if (!0 === t.hide) return t;
                        t.hide = !0, t.attributes["x-out-of-boundaries"] = ""
                    } else {
                        if (!1 === t.hide) return t;
                        t.hide = !1, t.attributes["x-out-of-boundaries"] = !1
                    }
                    return t
                }
            }, computeStyle: {
                order: 850, enabled: !0, fn: function (t, e) {
                    var i = e.x, n = e.y, o = t.offsets.popper, s = C(t.instance.modifiers, function (t) {
                        return "applyStyle" === t.name
                    }).gpuAcceleration;
                    void 0 !== s && console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");
                    var a = void 0 === s ? e.gpuAcceleration : s, r = d(c(t.instance.popper)),
                        l = {position: o.position},
                        e = {left: P(o.left), top: P(o.top), bottom: P(o.bottom), right: P(o.right)},
                        s = "bottom" === i ? "top" : "bottom", o = "right" === n ? "left" : "right", i = T("transform"),
                        n = "bottom" == s ? -r.height + e.bottom : e.top,
                        e = "right" == o ? -r.width + e.right : e.left;
                    a && i ? (l[i] = "translate3d(" + e + "px, " + n + "px, 0)", l[s] = 0, l[o] = 0, l.willChange = "transform") : (i = "right" == o ? -1 : 1, l[s] = n * ("bottom" == s ? -1 : 1), l[o] = e * i, l.willChange = s + ", " + o);
                    o = {"x-placement": t.placement};
                    return t.attributes = q({}, o, t.attributes), t.styles = q({}, l, t.styles), t.arrowStyles = q({}, t.offsets.arrow, t.arrowStyles), t
                }, gpuAcceleration: !0, x: "bottom", y: "right"
            }, applyStyle: {
                order: 900, enabled: !0, fn: function (t) {
                    return k(t.instance.popper, t.styles), e = t.instance.popper, i = t.attributes, Object.keys(i).forEach(function (t) {
                        !1 === i[t] ? e.removeAttribute(t) : e.setAttribute(t, i[t])
                    }), t.arrowElement && Object.keys(t.arrowStyles).length && k(t.arrowElement, t.arrowStyles), t;
                    var e, i
                }, onLoad: function (t, e, i, n, o) {
                    var s = u(0, e, t),
                        t = l(i.placement, s, e, t, i.modifiers.flip.boundariesElement, i.modifiers.flip.padding);
                    return e.setAttribute("x-placement", t), k(e, {position: "absolute"}), i
                }, gpuAcceleration: void 0
            }
        }
    }, F
}), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? e(exports, require("jquery"), require("popper.js")) : "function" == typeof define && define.amd ? define(["exports", "jquery", "popper.js"], e) : e(t.bootstrap = {}, t.jQuery, t.Popper)
}(this, function (t, e, o) {
    "use strict";

    function n(t, e) {
        for (var i = 0; i < e.length; i++) {
            var n = e[i];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }

    function _(t, e, i) {
        return e && n(t.prototype, e), i && n(t, i), t
    }

    function C() {
        return (C = Object.assign || function (t) {
            for (var e = 1; e < arguments.length; e++) {
                var i, n = arguments[e];
                for (i in n) Object.prototype.hasOwnProperty.call(n, i) && (t[i] = n[i])
            }
            return t
        }).apply(this, arguments)
    }

    e = e && e.hasOwnProperty("default") ? e.default : e, o = o && o.hasOwnProperty("default") ? o.default : o;
    var s, i, a, r, l, c, d, u, h, p, f, m, g, v, b, y, w, x, S, T, $, E, k, I, O, z, A, P, D, L, M, N, H, j, B, R, W,
        F, q, V, U, G, Y, X, K, Z, Q, J, tt, et, it, nt, ot, st, at, rt, lt, ct, dt, ut, ht, pt, ft, mt,
        gt = (pt = e, ft = !1, mt = {
            TRANSITION_END: "bsTransitionEnd", getUID: function (t) {
                for (; t += ~~(1e6 * Math.random()), document.getElementById(t);) ;
                return t
            }, getSelectorFromElement: function (t) {
                var e = t.getAttribute("data-target");
                "#" === (e = !e || "#" === e ? t.getAttribute("href") || "" : e).charAt(0) && (t = e, e = t = "function" == typeof pt.escapeSelector ? pt.escapeSelector(t).substr(1) : t.replace(/(:|\.|\[|\]|,|=|@)/g, "\\$1"));
                try {
                    return 0 < pt(document).find(e).length ? e : null
                } catch (t) {
                    return null
                }
            }, reflow: function (t) {
                return t.offsetHeight
            }, triggerTransitionEnd: function (t) {
                pt(t).trigger(ft.end)
            }, supportsTransitionEnd: function () {
                return Boolean(ft)
            }, isElement: function (t) {
                return (t[0] || t).nodeType
            }, typeCheckConfig: function (t, e, i) {
                for (var n in i) if (Object.prototype.hasOwnProperty.call(i, n)) {
                    var o = i[n], s = e[n],
                        s = s && mt.isElement(s) ? "element" : {}.toString.call(s).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
                    if (!new RegExp(o).test(s)) throw new Error(t.toUpperCase() + ': Option "' + n + '" provided type "' + s + '" but expected type "' + o + '".')
                }
            }
        }, ft = ("undefined" == typeof window || !window.QUnit) && {end: "transitionend"}, pt.fn.emulateTransitionEnd = function (t) {
            var e = this, i = !1;
            return pt(this).one(mt.TRANSITION_END, function () {
                i = !0
            }), setTimeout(function () {
                i || mt.triggerTransitionEnd(e)
            }, t), this
        }, mt.supportsTransitionEnd() && (pt.event.special[mt.TRANSITION_END] = {
            bindType: ft.end,
            delegateType: ft.end,
            handle: function (t) {
                if (pt(t.target).is(this)) return t.handleObj.handler.apply(this, arguments)
            }
        }), mt), vt = (wt = "." + (a = "bs.alert"), r = (s = e).fn[i = "alert"], l = {
            CLOSE: "close" + wt,
            CLOSED: "closed" + wt,
            CLICK_DATA_API: "click" + wt + ".data-api"
        }, (Tt = zt.prototype).close = function (t) {
            t = t || this._element;
            t = this._getRootElement(t);
            this._triggerCloseEvent(t).isDefaultPrevented() || this._removeElement(t)
        }, Tt.dispose = function () {
            s.removeData(this._element, a), this._element = null
        }, Tt._getRootElement = function (t) {
            var e = gt.getSelectorFromElement(t), i = !1;
            return i = (i = e ? s(e)[0] : i) || s(t).closest(".alert")[0]
        }, Tt._triggerCloseEvent = function (t) {
            var e = s.Event(l.CLOSE);
            return s(t).trigger(e), e
        }, Tt._removeElement = function (e) {
            var i = this;
            s(e).removeClass("show"), gt.supportsTransitionEnd() && s(e).hasClass("fade") ? s(e).one(gt.TRANSITION_END, function (t) {
                return i._destroyElement(e, t)
            }).emulateTransitionEnd(150) : this._destroyElement(e)
        }, Tt._destroyElement = function (t) {
            s(t).detach().trigger(l.CLOSED).remove()
        }, zt._jQueryInterface = function (i) {
            return this.each(function () {
                var t = s(this), e = t.data(a);
                e || (e = new zt(this), t.data(a, e)), "close" === i && e[i](this)
            })
        }, zt._handleDismiss = function (e) {
            return function (t) {
                t && t.preventDefault(), e.close(this)
            }
        }, _(zt, null, [{
            key: "VERSION", get: function () {
                return "4.0.0"
            }
        }]), c = zt, s(document).on(l.CLICK_DATA_API, '[data-dismiss="alert"]', c._handleDismiss(new c)), s.fn[i] = c._jQueryInterface, s.fn[i].Constructor = c, s.fn[i].noConflict = function () {
            return s.fn[i] = r, c._jQueryInterface
        }, c),
        bt = (xt = "." + (h = "bs.button"), p = (d = e).fn[u = "button"], f = "active", St = '[data-toggle^="button"]', Ct = {
            CLICK_DATA_API: "click" + xt + (_t = ".data-api"),
            FOCUS_BLUR_DATA_API: "focus" + xt + _t + " blur" + xt + _t
        }, ($t = Ot.prototype).toggle = function () {
            var t = !0, e = !0, i = d(this._element).closest('[data-toggle="buttons"]')[0];
            if (i) {
                var n, o = d(this._element).find("input")[0];
                if (o) {
                    if ("radio" === o.type && (o.checked && d(this._element).hasClass(f) ? t = !1 : (n = d(i).find(".active")[0]) && d(n).removeClass(f)), t) {
                        if (o.hasAttribute("disabled") || i.hasAttribute("disabled") || o.classList.contains("disabled") || i.classList.contains("disabled")) return;
                        o.checked = !d(this._element).hasClass(f), d(o).trigger("change")
                    }
                    o.focus(), e = !1
                }
            }
            e && this._element.setAttribute("aria-pressed", !d(this._element).hasClass(f)), t && d(this._element).toggleClass(f)
        }, $t.dispose = function () {
            d.removeData(this._element, h), this._element = null
        }, Ot._jQueryInterface = function (e) {
            return this.each(function () {
                var t = d(this).data(h);
                t || (t = new Ot(this), d(this).data(h, t)), "toggle" === e && t[e]()
            })
        }, _(Ot, null, [{
            key: "VERSION", get: function () {
                return "4.0.0"
            }
        }]), m = Ot, d(document).on(Ct.CLICK_DATA_API, St, function (t) {
            t.preventDefault();
            t = t.target;
            d(t).hasClass("btn") || (t = d(t).closest(".btn")), m._jQueryInterface.call(d(t), "toggle")
        }).on(Ct.FOCUS_BLUR_DATA_API, St, function (t) {
            var e = d(t.target).closest(".btn")[0];
            d(e).toggleClass("focus", /^focus(in)?$/.test(t.type))
        }), d.fn[u] = m._jQueryInterface, d.fn[u].Constructor = m, d.fn[u].noConflict = function () {
            return d.fn[u] = p, m._jQueryInterface
        }, m), yt = function (d) {
            var t, e = "carousel", o = "bs.carousel", i = "." + o, n = d.fn[e],
                s = {interval: 5e3, keyboard: !0, slide: !1, pause: "hover", wrap: !0}, a = {
                    interval: "(number|boolean)",
                    keyboard: "boolean",
                    slide: "(boolean|string)",
                    pause: "(string|boolean)",
                    wrap: "boolean"
                }, u = "next", r = "prev", h = {
                    SLIDE: "slide" + i,
                    SLID: "slid" + i,
                    KEYDOWN: "keydown" + i,
                    MOUSEENTER: "mouseenter" + i,
                    MOUSELEAVE: "mouseleave" + i,
                    TOUCHEND: "touchend" + i,
                    LOAD_DATA_API: "load" + i + ".data-api",
                    CLICK_DATA_API: "click" + i + ".data-api"
                }, p = "active", l = ".active", f = ".active.carousel-item", c = ".carousel-item",
                m = ".carousel-item-next, .carousel-item-prev", g = ".carousel-indicators",
                v = "[data-slide], [data-slide-to]", b = '[data-ride="carousel"]',
                y = ((t = w.prototype).next = function () {
                    this._isSliding || this._slide(u)
                }, t.nextWhenVisible = function () {
                    !document.hidden && d(this._element).is(":visible") && "hidden" !== d(this._element).css("visibility") && this.next()
                }, t.prev = function () {
                    this._isSliding || this._slide(r)
                }, t.pause = function (t) {
                    t || (this._isPaused = !0), d(this._element).find(m)[0] && gt.supportsTransitionEnd() && (gt.triggerTransitionEnd(this._element), this.cycle(!0)), clearInterval(this._interval), this._interval = null
                }, t.cycle = function (t) {
                    t || (this._isPaused = !1), this._interval && (clearInterval(this._interval), this._interval = null), this._config.interval && !this._isPaused && (this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval))
                }, t.to = function (t) {
                    var e = this;
                    this._activeElement = d(this._element).find(f)[0];
                    var i = this._getItemIndex(this._activeElement);
                    if (!(t > this._items.length - 1 || t < 0)) if (this._isSliding) d(this._element).one(h.SLID, function () {
                        return e.to(t)
                    }); else {
                        if (i === t) return this.pause(), void this.cycle();
                        this._slide(i < t ? u : r, this._items[t])
                    }
                }, t.dispose = function () {
                    d(this._element).off(i), d.removeData(this._element, o), this._items = null, this._config = null, this._element = null, this._interval = null, this._isPaused = null, this._isSliding = null, this._activeElement = null, this._indicatorsElement = null
                }, t._getConfig = function (t) {
                    return t = C({}, s, t), gt.typeCheckConfig(e, t, a), t
                }, t._addEventListeners = function () {
                    var e = this;
                    this._config.keyboard && d(this._element).on(h.KEYDOWN, function (t) {
                        return e._keydown(t)
                    }), "hover" === this._config.pause && (d(this._element).on(h.MOUSEENTER, function (t) {
                        return e.pause(t)
                    }).on(h.MOUSELEAVE, function (t) {
                        return e.cycle(t)
                    }), "ontouchstart" in document.documentElement && d(this._element).on(h.TOUCHEND, function () {
                        e.pause(), e.touchTimeout && clearTimeout(e.touchTimeout), e.touchTimeout = setTimeout(function (t) {
                            return e.cycle(t)
                        }, 500 + e._config.interval)
                    }))
                }, t._keydown = function (t) {
                    if (!/input|textarea/i.test(t.target.tagName)) switch (t.which) {
                        case 37:
                            t.preventDefault(), this.prev();
                            break;
                        case 39:
                            t.preventDefault(), this.next()
                    }
                }, t._getItemIndex = function (t) {
                    return this._items = d.makeArray(d(t).parent().find(c)), this._items.indexOf(t)
                }, t._getItemByDirection = function (t, e) {
                    var i = t === u, n = t === r, o = this._getItemIndex(e), s = this._items.length - 1;
                    if ((n && 0 === o || i && o === s) && !this._config.wrap) return e;
                    t = (o + (t === r ? -1 : 1)) % this._items.length;
                    return -1 == t ? this._items[this._items.length - 1] : this._items[t]
                }, t._triggerSlideEvent = function (t, e) {
                    var i = this._getItemIndex(t), n = this._getItemIndex(d(this._element).find(f)[0]),
                        i = d.Event(h.SLIDE, {relatedTarget: t, direction: e, from: n, to: i});
                    return d(this._element).trigger(i), i
                }, t._setActiveIndicatorElement = function (t) {
                    this._indicatorsElement && (d(this._indicatorsElement).find(l).removeClass(p), (t = this._indicatorsElement.children[this._getItemIndex(t)]) && d(t).addClass(p))
                }, t._slide = function (t, e) {
                    var i, n, o, s = this, a = d(this._element).find(f)[0], r = this._getItemIndex(a),
                        l = e || a && this._getItemByDirection(t, a), c = this._getItemIndex(l),
                        e = Boolean(this._interval),
                        t = t === u ? (i = "carousel-item-left", n = "carousel-item-next", "left") : (i = "carousel-item-right", n = "carousel-item-prev", "right");
                    l && d(l).hasClass(p) ? this._isSliding = !1 : !this._triggerSlideEvent(l, t).isDefaultPrevented() && a && l && (this._isSliding = !0, e && this.pause(), this._setActiveIndicatorElement(l), o = d.Event(h.SLID, {
                        relatedTarget: l,
                        direction: t,
                        from: r,
                        to: c
                    }), gt.supportsTransitionEnd() && d(this._element).hasClass("slide") ? (d(l).addClass(n), gt.reflow(l), d(a).addClass(i), d(l).addClass(i), d(a).one(gt.TRANSITION_END, function () {
                        d(l).removeClass(i + " " + n).addClass(p), d(a).removeClass(p + " " + n + " " + i), s._isSliding = !1, setTimeout(function () {
                            return d(s._element).trigger(o)
                        }, 0)
                    }).emulateTransitionEnd(600)) : (d(a).removeClass(p), d(l).addClass(p), this._isSliding = !1, d(this._element).trigger(o)), e && this.cycle())
                }, w._jQueryInterface = function (n) {
                    return this.each(function () {
                        var t = d(this).data(o), e = C({}, s, d(this).data());
                        "object" == typeof n && (e = C({}, e, n));
                        var i = "string" == typeof n ? n : e.slide;
                        if (t || (t = new w(this, e), d(this).data(o, t)), "number" == typeof n) t.to(n); else if ("string" == typeof i) {
                            if (void 0 === t[i]) throw new TypeError('No method named "' + i + '"');
                            t[i]()
                        } else e.interval && (t.pause(), t.cycle())
                    })
                }, w._dataApiClickHandler = function (t) {
                    var e, i, n = gt.getSelectorFromElement(this);
                    !n || (e = d(n)[0]) && d(e).hasClass("carousel") && (i = C({}, d(e).data(), d(this).data()), (n = this.getAttribute("data-slide-to")) && (i.interval = !1), w._jQueryInterface.call(d(e), i), n && d(e).data(o).to(n), t.preventDefault())
                }, _(w, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0"
                    }
                }, {
                    key: "Default", get: function () {
                        return s
                    }
                }]), w);

            function w(t, e) {
                this._items = null, this._interval = null, this._activeElement = null, this._isPaused = !1, this._isSliding = !1, this.touchTimeout = null, this._config = this._getConfig(e), this._element = d(t)[0], this._indicatorsElement = d(this._element).find(g)[0], this._addEventListeners()
            }

            return d(document).on(h.CLICK_DATA_API, v, y._dataApiClickHandler), d(window).on(h.LOAD_DATA_API, function () {
                d(b).each(function () {
                    var t = d(this);
                    y._jQueryInterface.call(t, t.data())
                })
            }), d.fn[e] = y._jQueryInterface, d.fn[e].Constructor = y, d.fn[e].noConflict = function () {
                return d.fn[e] = n, y._jQueryInterface
            }, y
        }(e), wt = function (a) {
            var e = "collapse", s = "bs.collapse", t = "." + s, i = a.fn[e], o = {toggle: !0, parent: ""},
                n = {toggle: "boolean", parent: "(string|element)"}, r = {
                    SHOW: "show" + t,
                    SHOWN: "shown" + t,
                    HIDE: "hide" + t,
                    HIDDEN: "hidden" + t,
                    CLICK_DATA_API: "click" + t + ".data-api"
                }, l = "show", c = "collapse", d = "collapsing", u = "collapsed", h = ".show, .collapsing",
                p = '[data-toggle="collapse"]', f = ((t = m.prototype).toggle = function () {
                    a(this._element).hasClass(l) ? this.hide() : this.show()
                }, t.show = function () {
                    var t, e, i, n, o = this;
                    this._isTransitioning || a(this._element).hasClass(l) || (n = this._parent && 0 === (n = a.makeArray(a(this._parent).find(h).filter('[data-parent="' + this._config.parent + '"]'))).length ? null : n) && (i = a(n).not(this._selector).data(s)) && i._isTransitioning || (t = a.Event(r.SHOW), a(this._element).trigger(t), t.isDefaultPrevented() || (n && (m._jQueryInterface.call(a(n).not(this._selector), "hide"), i || a(n).data(s, null)), e = this._getDimension(), a(this._element).removeClass(c).addClass(d), (this._element.style[e] = 0) < this._triggerArray.length && a(this._triggerArray).removeClass(u).attr("aria-expanded", !0), this.setTransitioning(!0), i = function () {
                        a(o._element).removeClass(d).addClass(c).addClass(l), o._element.style[e] = "", o.setTransitioning(!1), a(o._element).trigger(r.SHOWN)
                    }, gt.supportsTransitionEnd() ? (n = "scroll" + (e[0].toUpperCase() + e.slice(1)), a(this._element).one(gt.TRANSITION_END, i).emulateTransitionEnd(600), this._element.style[e] = this._element[n] + "px") : i()))
                }, t.hide = function () {
                    var t = this;
                    if (!this._isTransitioning && a(this._element).hasClass(l)) {
                        var e = a.Event(r.HIDE);
                        if (a(this._element).trigger(e), !e.isDefaultPrevented()) {
                            var i = this._getDimension();
                            if (this._element.style[i] = this._element.getBoundingClientRect()[i] + "px", gt.reflow(this._element), a(this._element).addClass(d).removeClass(c).removeClass(l), 0 < this._triggerArray.length) for (var n = 0; n < this._triggerArray.length; n++) {
                                var o = this._triggerArray[n], s = gt.getSelectorFromElement(o);
                                null !== s && (a(s).hasClass(l) || a(o).addClass(u).attr("aria-expanded", !1))
                            }
                            this.setTransitioning(!0);
                            e = function () {
                                t.setTransitioning(!1), a(t._element).removeClass(d).addClass(c).trigger(r.HIDDEN)
                            };
                            this._element.style[i] = "", gt.supportsTransitionEnd() ? a(this._element).one(gt.TRANSITION_END, e).emulateTransitionEnd(600) : e()
                        }
                    }
                }, t.setTransitioning = function (t) {
                    this._isTransitioning = t
                }, t.dispose = function () {
                    a.removeData(this._element, s), this._config = null, this._parent = null, this._element = null, this._triggerArray = null, this._isTransitioning = null
                }, t._getConfig = function (t) {
                    return (t = C({}, o, t)).toggle = Boolean(t.toggle), gt.typeCheckConfig(e, t, n), t
                }, t._getDimension = function () {
                    return a(this._element).hasClass("width") ? "width" : "height"
                }, t._getParent = function () {
                    var i = this, t = null;
                    gt.isElement(this._config.parent) ? (t = this._config.parent, void 0 !== this._config.parent.jquery && (t = this._config.parent[0])) : t = a(this._config.parent)[0];
                    var e = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';
                    return a(t).find(e).each(function (t, e) {
                        i._addAriaAndCollapsedClass(m._getTargetFromElement(e), [e])
                    }), t
                }, t._addAriaAndCollapsedClass = function (t, e) {
                    t && (t = a(t).hasClass(l), 0 < e.length && a(e).toggleClass(u, !t).attr("aria-expanded", t))
                }, m._getTargetFromElement = function (t) {
                    t = gt.getSelectorFromElement(t);
                    return t ? a(t)[0] : null
                }, m._jQueryInterface = function (n) {
                    return this.each(function () {
                        var t = a(this), e = t.data(s), i = C({}, o, t.data(), "object" == typeof n && n);
                        if (!e && i.toggle && /show|hide/.test(n) && (i.toggle = !1), e || (e = new m(this, i), t.data(s, e)), "string" == typeof n) {
                            if (void 0 === e[n]) throw new TypeError('No method named "' + n + '"');
                            e[n]()
                        }
                    })
                }, _(m, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0"
                    }
                }, {
                    key: "Default", get: function () {
                        return o
                    }
                }]), m);

            function m(t, e) {
                this._isTransitioning = !1, this._element = t, this._config = this._getConfig(e), this._triggerArray = a.makeArray(a('[data-toggle="collapse"][href="#' + t.id + '"],[data-toggle="collapse"][data-target="#' + t.id + '"]'));
                for (var i = a(p), n = 0; n < i.length; n++) {
                    var o = i[n], s = gt.getSelectorFromElement(o);
                    null !== s && 0 < a(s).filter(t).length && (this._selector = s, this._triggerArray.push(o))
                }
                this._parent = this._config.parent ? this._getParent() : null, this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray), this._config.toggle && this.toggle()
            }

            return a(document).on(r.CLICK_DATA_API, p, function (t) {
                "A" === t.currentTarget.tagName && t.preventDefault();
                var i = a(this), t = gt.getSelectorFromElement(this);
                a(t).each(function () {
                    var t = a(this), e = t.data(s) ? "toggle" : i.data();
                    f._jQueryInterface.call(t, e)
                })
            }), a.fn[e] = f._jQueryInterface, a.fn[e].Constructor = f, a.fn[e].noConflict = function () {
                return a.fn[e] = i, f._jQueryInterface
            }, f
        }(e),
        xt = (J = "dropdown", et = "." + (tt = "bs.dropdown"), Tt = ".data-api", it = (Q = e).fn[J], nt = new RegExp("38|40|27"), ot = {
            HIDE: "hide" + et,
            HIDDEN: "hidden" + et,
            SHOW: "show" + et,
            SHOWN: "shown" + et,
            CLICK: "click" + et,
            CLICK_DATA_API: "click" + et + Tt,
            KEYDOWN_DATA_API: "keydown" + et + Tt,
            KEYUP_DATA_API: "keyup" + et + Tt
        }, st = "disabled", at = "show", rt = "dropdown-menu-right", lt = '[data-toggle="dropdown"]', ct = ".dropdown-menu", dt = {
            offset: 0,
            flip: !0,
            boundary: "scrollParent"
        }, ut = {
            offset: "(number|string|function)",
            flip: "boolean",
            boundary: "(string|element)"
        }, (Tt = It.prototype).toggle = function () {
            if (!this._element.disabled && !Q(this._element).hasClass(st)) {
                var t = It._getParentFromElement(this._element), e = Q(this._menu).hasClass(at);
                if (It._clearMenus(), !e) {
                    var i = {relatedTarget: this._element}, e = Q.Event(ot.SHOW, i);
                    if (Q(t).trigger(e), !e.isDefaultPrevented()) {
                        if (!this._inNavbar) {
                            if (void 0 === o) throw new TypeError("Bootstrap dropdown require Popper.js (https://popper.js.org)");
                            e = this._element;
                            Q(t).hasClass("dropup") && (Q(this._menu).hasClass("dropdown-menu-left") || Q(this._menu).hasClass(rt)) && (e = t), "scrollParent" !== this._config.boundary && Q(t).addClass("position-static"), this._popper = new o(e, this._menu, this._getPopperConfig())
                        }
                        "ontouchstart" in document.documentElement && 0 === Q(t).closest(".navbar-nav").length && Q("body").children().on("mouseover", null, Q.noop), this._element.focus(), this._element.setAttribute("aria-expanded", !0), Q(this._menu).toggleClass(at), Q(t).toggleClass(at).trigger(Q.Event(ot.SHOWN, i))
                    }
                }
            }
        }, Tt.dispose = function () {
            Q.removeData(this._element, tt), Q(this._element).off(et), this._element = null, (this._menu = null) !== this._popper && (this._popper.destroy(), this._popper = null)
        }, Tt.update = function () {
            this._inNavbar = this._detectNavbar(), null !== this._popper && this._popper.scheduleUpdate()
        }, Tt._addEventListeners = function () {
            var e = this;
            Q(this._element).on(ot.CLICK, function (t) {
                t.preventDefault(), t.stopPropagation(), e.toggle()
            })
        }, Tt._getConfig = function (t) {
            return t = C({}, this.constructor.Default, Q(this._element).data(), t), gt.typeCheckConfig(J, t, this.constructor.DefaultType), t
        }, Tt._getMenuElement = function () {
            var t;
            return this._menu || (t = It._getParentFromElement(this._element), this._menu = Q(t).find(ct)[0]), this._menu
        }, Tt._getPlacement = function () {
            var t = Q(this._element).parent(), e = "bottom-start";
            return t.hasClass("dropup") ? (e = "top-start", Q(this._menu).hasClass(rt) && (e = "top-end")) : t.hasClass("dropright") ? e = "right-start" : t.hasClass("dropleft") ? e = "left-start" : Q(this._menu).hasClass(rt) && (e = "bottom-end"), e
        }, Tt._detectNavbar = function () {
            return 0 < Q(this._element).closest(".navbar").length
        }, Tt._getPopperConfig = function () {
            var e = this, t = {};
            return "function" == typeof this._config.offset ? t.fn = function (t) {
                return t.offsets = C({}, t.offsets, e._config.offset(t.offsets) || {}), t
            } : t.offset = this._config.offset, {
                placement: this._getPlacement(),
                modifiers: {
                    offset: t,
                    flip: {enabled: this._config.flip},
                    preventOverflow: {boundariesElement: this._config.boundary}
                }
            }
        }, It._jQueryInterface = function (e) {
            return this.each(function () {
                var t = Q(this).data(tt);
                if (t || (t = new It(this, "object" == typeof e ? e : null), Q(this).data(tt, t)), "string" == typeof e) {
                    if (void 0 === t[e]) throw new TypeError('No method named "' + e + '"');
                    t[e]()
                }
            })
        }, It._clearMenus = function (t) {
            if (!t || 3 !== t.which && ("keyup" !== t.type || 9 === t.which)) for (var e = Q.makeArray(Q(lt)), i = 0; i < e.length; i++) {
                var n, o = It._getParentFromElement(e[i]), s = Q(e[i]).data(tt), a = {relatedTarget: e[i]};
                s && (n = s._menu, !Q(o).hasClass(at) || t && ("click" === t.type && /input|textarea/i.test(t.target.tagName) || "keyup" === t.type && 9 === t.which) && Q.contains(o, t.target) || (s = Q.Event(ot.HIDE, a), Q(o).trigger(s), s.isDefaultPrevented() || ("ontouchstart" in document.documentElement && Q("body").children().off("mouseover", null, Q.noop), e[i].setAttribute("aria-expanded", "false"), Q(n).removeClass(at), Q(o).removeClass(at).trigger(Q.Event(ot.HIDDEN, a)))))
            }
        }, It._getParentFromElement = function (t) {
            var e, i = gt.getSelectorFromElement(t);
            return (e = i ? Q(i)[0] : e) || t.parentNode
        }, It._dataApiKeydownHandler = function (t) {
            var e, i, n;
            (/input|textarea/i.test(t.target.tagName) ? 32 === t.which || 27 !== t.which && (40 !== t.which && 38 !== t.which || Q(t.target).closest(ct).length) : !nt.test(t.which)) || (t.preventDefault(), t.stopPropagation(), this.disabled || Q(this).hasClass(st)) || (n = It._getParentFromElement(this), ((i = Q(n).hasClass(at)) || 27 === t.which && 32 === t.which) && (!i || 27 !== t.which && 32 !== t.which) ? 0 !== (e = Q(n).find(".dropdown-menu .dropdown-item:not(.disabled)").get()).length && (i = e.indexOf(t.target), 38 === t.which && 0 < i && i--, 40 === t.which && i < e.length - 1 && i++, e[i = i < 0 ? 0 : i].focus()) : (27 === t.which && (n = Q(n).find(lt)[0], Q(n).trigger("focus")), Q(this).trigger("click")))
        }, _(It, null, [{
            key: "VERSION", get: function () {
                return "4.0.0"
            }
        }, {
            key: "Default", get: function () {
                return dt
            }
        }, {
            key: "DefaultType", get: function () {
                return ut
            }
        }]), ht = It, Q(document).on(ot.KEYDOWN_DATA_API, lt, ht._dataApiKeydownHandler).on(ot.KEYDOWN_DATA_API, ct, ht._dataApiKeydownHandler).on(ot.CLICK_DATA_API + " " + ot.KEYUP_DATA_API, ht._clearMenus).on(ot.CLICK_DATA_API, lt, function (t) {
            t.preventDefault(), t.stopPropagation(), ht._jQueryInterface.call(Q(this), "toggle")
        }).on(ot.CLICK_DATA_API, ".dropdown form", function (t) {
            t.stopPropagation()
        }), Q.fn[J] = ht._jQueryInterface, Q.fn[J].Constructor = ht, Q.fn[J].noConflict = function () {
            return Q.fn[J] = it, ht._jQueryInterface
        }, ht), _t = function (s) {
            var t, a = "bs.modal", e = "." + a, i = s.fn.modal, n = {backdrop: !0, keyboard: !0, focus: !0, show: !0},
                o = {backdrop: "(boolean|string)", keyboard: "boolean", focus: "boolean", show: "boolean"}, r = {
                    HIDE: "hide" + e,
                    HIDDEN: "hidden" + e,
                    SHOW: "show" + e,
                    SHOWN: "shown" + e,
                    FOCUSIN: "focusin" + e,
                    RESIZE: "resize" + e,
                    CLICK_DISMISS: "click.dismiss" + e,
                    KEYDOWN_DISMISS: "keydown.dismiss" + e,
                    MOUSEUP_DISMISS: "mouseup.dismiss" + e,
                    MOUSEDOWN_DISMISS: "mousedown.dismiss" + e,
                    CLICK_DATA_API: "click" + e + ".data-api"
                }, l = "modal-open", c = "fade", d = "show", u = ".modal-dialog", h = '[data-toggle="modal"]',
                p = '[data-dismiss="modal"]', f = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top", m = ".sticky-top",
                g = ".navbar-toggler", v = ((t = b.prototype).toggle = function (t) {
                    return this._isShown ? this.hide() : this.show(t)
                }, t.show = function (t) {
                    var e, i = this;
                    this._isTransitioning || this._isShown || (gt.supportsTransitionEnd() && s(this._element).hasClass(c) && (this._isTransitioning = !0), e = s.Event(r.SHOW, {relatedTarget: t}), s(this._element).trigger(e), this._isShown || e.isDefaultPrevented() || (this._isShown = !0, this._checkScrollbar(), this._setScrollbar(), this._adjustDialog(), s(document.body).addClass(l), this._setEscapeEvent(), this._setResizeEvent(), s(this._element).on(r.CLICK_DISMISS, p, function (t) {
                        return i.hide(t)
                    }), s(this._dialog).on(r.MOUSEDOWN_DISMISS, function () {
                        s(i._element).one(r.MOUSEUP_DISMISS, function (t) {
                            s(t.target).is(i._element) && (i._ignoreBackdropClick = !0)
                        })
                    }), this._showBackdrop(function () {
                        return i._showElement(t)
                    })))
                }, t.hide = function (t) {
                    var e = this;
                    t && t.preventDefault(), !this._isTransitioning && this._isShown && (t = s.Event(r.HIDE), s(this._element).trigger(t), this._isShown && !t.isDefaultPrevented() && (this._isShown = !1, (t = gt.supportsTransitionEnd() && s(this._element).hasClass(c)) && (this._isTransitioning = !0), this._setEscapeEvent(), this._setResizeEvent(), s(document).off(r.FOCUSIN), s(this._element).removeClass(d), s(this._element).off(r.CLICK_DISMISS), s(this._dialog).off(r.MOUSEDOWN_DISMISS), t ? s(this._element).one(gt.TRANSITION_END, function (t) {
                        return e._hideModal(t)
                    }).emulateTransitionEnd(300) : this._hideModal()))
                }, t.dispose = function () {
                    s.removeData(this._element, a), s(window, document, this._element, this._backdrop).off(e), this._config = null, this._element = null, this._dialog = null, this._backdrop = null, this._isShown = null, this._isBodyOverflowing = null, this._ignoreBackdropClick = null, this._scrollbarWidth = null
                }, t.handleUpdate = function () {
                    this._adjustDialog()
                }, t._getConfig = function (t) {
                    return t = C({}, n, t), gt.typeCheckConfig("modal", t, o), t
                }, t._showElement = function (t) {
                    var e = this, i = gt.supportsTransitionEnd() && s(this._element).hasClass(c);
                    this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element), this._element.style.display = "block", this._element.removeAttribute("aria-hidden"), this._element.scrollTop = 0, i && gt.reflow(this._element), s(this._element).addClass(d), this._config.focus && this._enforceFocus();

                    function n() {
                        e._config.focus && e._element.focus(), e._isTransitioning = !1, s(e._element).trigger(o)
                    }

                    var o = s.Event(r.SHOWN, {relatedTarget: t});
                    i ? s(this._dialog).one(gt.TRANSITION_END, n).emulateTransitionEnd(300) : n()
                }, t._enforceFocus = function () {
                    var e = this;
                    s(document).off(r.FOCUSIN).on(r.FOCUSIN, function (t) {
                        document !== t.target && e._element !== t.target && 0 === s(e._element).has(t.target).length && e._element.focus()
                    })
                }, t._setEscapeEvent = function () {
                    var e = this;
                    this._isShown && this._config.keyboard ? s(this._element).on(r.KEYDOWN_DISMISS, function (t) {
                        27 === t.which && (t.preventDefault(), e.hide())
                    }) : this._isShown || s(this._element).off(r.KEYDOWN_DISMISS)
                }, t._setResizeEvent = function () {
                    var e = this;
                    this._isShown ? s(window).on(r.RESIZE, function (t) {
                        return e.handleUpdate(t)
                    }) : s(window).off(r.RESIZE)
                }, t._hideModal = function () {
                    var t = this;
                    this._element.style.display = "none", this._element.setAttribute("aria-hidden", !0), this._isTransitioning = !1, this._showBackdrop(function () {
                        s(document.body).removeClass(l), t._resetAdjustments(), t._resetScrollbar(), s(t._element).trigger(r.HIDDEN)
                    })
                }, t._removeBackdrop = function () {
                    this._backdrop && (s(this._backdrop).remove(), this._backdrop = null)
                }, t._showBackdrop = function (t) {
                    var e, i = this, n = s(this._element).hasClass(c) ? c : "";
                    this._isShown && this._config.backdrop ? (e = gt.supportsTransitionEnd() && n, this._backdrop = document.createElement("div"), this._backdrop.className = "modal-backdrop", n && s(this._backdrop).addClass(n), s(this._backdrop).appendTo(document.body), s(this._element).on(r.CLICK_DISMISS, function (t) {
                        i._ignoreBackdropClick ? i._ignoreBackdropClick = !1 : t.target === t.currentTarget && ("static" === i._config.backdrop ? i._element.focus() : i.hide())
                    }), e && gt.reflow(this._backdrop), s(this._backdrop).addClass(d), t && (e ? s(this._backdrop).one(gt.TRANSITION_END, t).emulateTransitionEnd(150) : t())) : !this._isShown && this._backdrop ? (s(this._backdrop).removeClass(d), e = function () {
                        i._removeBackdrop(), t && t()
                    }, gt.supportsTransitionEnd() && s(this._element).hasClass(c) ? s(this._backdrop).one(gt.TRANSITION_END, e).emulateTransitionEnd(150) : e()) : t && t()
                }, t._adjustDialog = function () {
                    var t = this._element.scrollHeight > document.documentElement.clientHeight;
                    !this._isBodyOverflowing && t && (this._element.style.paddingLeft = this._scrollbarWidth + "px"), this._isBodyOverflowing && !t && (this._element.style.paddingRight = this._scrollbarWidth + "px")
                }, t._resetAdjustments = function () {
                    this._element.style.paddingLeft = "", this._element.style.paddingRight = ""
                }, t._checkScrollbar = function () {
                    var t = document.body.getBoundingClientRect();
                    this._isBodyOverflowing = t.left + t.right < window.innerWidth, this._scrollbarWidth = this._getScrollbarWidth()
                }, t._setScrollbar = function () {
                    var t, e, o = this;
                    this._isBodyOverflowing && (s(f).each(function (t, e) {
                        var i = s(e)[0].style.paddingRight, n = s(e).css("padding-right");
                        s(e).data("padding-right", i).css("padding-right", parseFloat(n) + o._scrollbarWidth + "px")
                    }), s(m).each(function (t, e) {
                        var i = s(e)[0].style.marginRight, n = s(e).css("margin-right");
                        s(e).data("margin-right", i).css("margin-right", parseFloat(n) - o._scrollbarWidth + "px")
                    }), s(g).each(function (t, e) {
                        var i = s(e)[0].style.marginRight, n = s(e).css("margin-right");
                        s(e).data("margin-right", i).css("margin-right", parseFloat(n) + o._scrollbarWidth + "px")
                    }), t = document.body.style.paddingRight, e = s("body").css("padding-right"), s("body").data("padding-right", t).css("padding-right", parseFloat(e) + this._scrollbarWidth + "px"))
                }, t._resetScrollbar = function () {
                    s(f).each(function (t, e) {
                        var i = s(e).data("padding-right");
                        void 0 !== i && s(e).css("padding-right", i).removeData("padding-right")
                    }), s(m + ", " + g).each(function (t, e) {
                        var i = s(e).data("margin-right");
                        void 0 !== i && s(e).css("margin-right", i).removeData("margin-right")
                    });
                    var t = s("body").data("padding-right");
                    void 0 !== t && s("body").css("padding-right", t).removeData("padding-right")
                }, t._getScrollbarWidth = function () {
                    var t = document.createElement("div");
                    t.className = "modal-scrollbar-measure", document.body.appendChild(t);
                    var e = t.getBoundingClientRect().width - t.clientWidth;
                    return document.body.removeChild(t), e
                }, b._jQueryInterface = function (i, n) {
                    return this.each(function () {
                        var t = s(this).data(a), e = C({}, b.Default, s(this).data(), "object" == typeof i && i);
                        if (t || (t = new b(this, e), s(this).data(a, t)), "string" == typeof i) {
                            if (void 0 === t[i]) throw new TypeError('No method named "' + i + '"');
                            t[i](n)
                        } else e.show && t.show(n)
                    })
                }, _(b, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0"
                    }
                }, {
                    key: "Default", get: function () {
                        return n
                    }
                }]), b);

            function b(t, e) {
                this._config = this._getConfig(e), this._element = t, this._dialog = s(t).find(u)[0], this._backdrop = null, this._isShown = !1, this._isBodyOverflowing = !1, this._ignoreBackdropClick = !1, this._originalBodyPadding = 0, this._scrollbarWidth = 0
            }

            return s(document).on(r.CLICK_DATA_API, h, function (t) {
                var e, i = this, n = gt.getSelectorFromElement(this);
                n && (e = s(n)[0]);
                n = s(e).data(a) ? "toggle" : C({}, s(e).data(), s(this).data());
                "A" !== this.tagName && "AREA" !== this.tagName || t.preventDefault();
                var o = s(e).one(r.SHOW, function (t) {
                    t.isDefaultPrevented() || o.one(r.HIDDEN, function () {
                        s(i).is(":visible") && i.focus()
                    })
                });
                v._jQueryInterface.call(s(e), n, this)
            }), s.fn.modal = v._jQueryInterface, s.fn.modal.Constructor = v, s.fn.modal.noConflict = function () {
                return s.fn.modal = i, v._jQueryInterface
            }, v
        }(e),
        Ct = (N = "tooltip", j = "." + (H = "bs.tooltip"), B = (M = e).fn[N], R = new RegExp("(^|\\s)bs-tooltip\\S+", "g"), q = {
            animation: !0,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !(F = {AUTO: "auto", TOP: "top", RIGHT: "right", BOTTOM: "bottom", LEFT: "left"}),
            selector: !(W = {
                animation: "boolean",
                template: "string",
                title: "(string|element|function)",
                trigger: "string",
                delay: "(number|object)",
                html: "boolean",
                selector: "(string|boolean)",
                placement: "(string|function)",
                offset: "(number|string)",
                container: "(string|element|boolean)",
                fallbackPlacement: "(string|array)",
                boundary: "(string|element)"
            }),
            placement: "top",
            offset: 0,
            container: !1,
            fallbackPlacement: "flip",
            boundary: "scrollParent"
        }, U = {
            HIDE: "hide" + j,
            HIDDEN: "hidden" + j,
            SHOW: (V = "show") + j,
            SHOWN: "shown" + j,
            INSERTED: "inserted" + j,
            CLICK: "click" + j,
            FOCUSIN: "focusin" + j,
            FOCUSOUT: "focusout" + j,
            MOUSEENTER: "mouseenter" + j,
            MOUSELEAVE: "mouseleave" + j
        }, G = "fade", Y = "show", X = "hover", K = "focus", ($t = kt.prototype).enable = function () {
            this._isEnabled = !0
        }, $t.disable = function () {
            this._isEnabled = !1
        }, $t.toggleEnabled = function () {
            this._isEnabled = !this._isEnabled
        }, $t.toggle = function (t) {
            var e, i;
            this._isEnabled && (t ? (e = this.constructor.DATA_KEY, (i = M(t.currentTarget).data(e)) || (i = new this.constructor(t.currentTarget, this._getDelegateConfig()), M(t.currentTarget).data(e, i)), i._activeTrigger.click = !i._activeTrigger.click, i._isWithActiveTrigger() ? i._enter(null, i) : i._leave(null, i)) : M(this.getTipElement()).hasClass(Y) ? this._leave(null, this) : this._enter(null, this))
        }, $t.dispose = function () {
            clearTimeout(this._timeout), M.removeData(this.element, this.constructor.DATA_KEY), M(this.element).off(this.constructor.EVENT_KEY), M(this.element).closest(".modal").off("hide.bs.modal"), this.tip && M(this.tip).remove(), this._isEnabled = null, this._timeout = null, this._hoverState = null, (this._activeTrigger = null) !== this._popper && this._popper.destroy(), this._popper = null, this.element = null, this.config = null, this.tip = null
        }, $t.show = function () {
            var e = this;
            if ("none" === M(this.element).css("display")) throw new Error("Please use show on visible elements");
            var t, i, n = M.Event(this.constructor.Event.SHOW);
            this.isWithContent() && this._isEnabled && (M(this.element).trigger(n), t = M.contains(this.element.ownerDocument.documentElement, this.element), !n.isDefaultPrevented() && t && (i = this.getTipElement(), n = gt.getUID(this.constructor.NAME), i.setAttribute("id", n), this.element.setAttribute("aria-describedby", n), this.setContent(), this.config.animation && M(i).addClass(G), t = "function" == typeof this.config.placement ? this.config.placement.call(this, i, this.element) : this.config.placement, n = this._getAttachment(t), this.addAttachmentClass(n), t = !1 === this.config.container ? document.body : M(this.config.container), M(i).data(this.constructor.DATA_KEY, this), M.contains(this.element.ownerDocument.documentElement, this.tip) || M(i).appendTo(t), M(this.element).trigger(this.constructor.Event.INSERTED), this._popper = new o(this.element, i, {
                placement: n,
                modifiers: {
                    offset: {offset: this.config.offset},
                    flip: {behavior: this.config.fallbackPlacement},
                    arrow: {element: ".arrow"},
                    preventOverflow: {boundariesElement: this.config.boundary}
                },
                onCreate: function (t) {
                    t.originalPlacement !== t.placement && e._handlePopperPlacementChange(t)
                },
                onUpdate: function (t) {
                    e._handlePopperPlacementChange(t)
                }
            }), M(i).addClass(Y), "ontouchstart" in document.documentElement && M("body").children().on("mouseover", null, M.noop), i = function () {
                e.config.animation && e._fixTransition();
                var t = e._hoverState;
                e._hoverState = null, M(e.element).trigger(e.constructor.Event.SHOWN), "out" === t && e._leave(null, e)
            }, gt.supportsTransitionEnd() && M(this.tip).hasClass(G) ? M(this.tip).one(gt.TRANSITION_END, i).emulateTransitionEnd(kt._TRANSITION_DURATION) : i()))
        }, $t.hide = function (t) {
            function e() {
                i._hoverState !== V && n.parentNode && n.parentNode.removeChild(n), i._cleanTipClass(), i.element.removeAttribute("aria-describedby"), M(i.element).trigger(i.constructor.Event.HIDDEN), null !== i._popper && i._popper.destroy(), t && t()
            }

            var i = this, n = this.getTipElement(), o = M.Event(this.constructor.Event.HIDE);
            M(this.element).trigger(o), o.isDefaultPrevented() || (M(n).removeClass(Y), "ontouchstart" in document.documentElement && M("body").children().off("mouseover", null, M.noop), this._activeTrigger.click = !1, this._activeTrigger[K] = !1, this._activeTrigger[X] = !1, gt.supportsTransitionEnd() && M(this.tip).hasClass(G) ? M(n).one(gt.TRANSITION_END, e).emulateTransitionEnd(150) : e(), this._hoverState = "")
        }, $t.update = function () {
            null !== this._popper && this._popper.scheduleUpdate()
        }, $t.isWithContent = function () {
            return Boolean(this.getTitle())
        }, $t.addAttachmentClass = function (t) {
            M(this.getTipElement()).addClass("bs-tooltip-" + t)
        }, $t.getTipElement = function () {
            return this.tip = this.tip || M(this.config.template)[0], this.tip
        }, $t.setContent = function () {
            var t = M(this.getTipElement());
            this.setElementContent(t.find(".tooltip-inner"), this.getTitle()), t.removeClass(G + " " + Y)
        }, $t.setElementContent = function (t, e) {
            var i = this.config.html;
            "object" == typeof e && (e.nodeType || e.jquery) ? i ? M(e).parent().is(t) || t.empty().append(e) : t.text(M(e).text()) : t[i ? "html" : "text"](e)
        }, $t.getTitle = function () {
            return this.element.getAttribute("data-original-title") || ("function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title)
        }, $t._getAttachment = function (t) {
            return F[t.toUpperCase()]
        }, $t._setListeners = function () {
            var i = this;
            this.config.trigger.split(" ").forEach(function (t) {
                var e;
                "click" === t ? M(i.element).on(i.constructor.Event.CLICK, i.config.selector, function (t) {
                    return i.toggle(t)
                }) : "manual" !== t && (e = t === X ? i.constructor.Event.MOUSEENTER : i.constructor.Event.FOCUSIN, t = t === X ? i.constructor.Event.MOUSELEAVE : i.constructor.Event.FOCUSOUT, M(i.element).on(e, i.config.selector, function (t) {
                    return i._enter(t)
                }).on(t, i.config.selector, function (t) {
                    return i._leave(t)
                })), M(i.element).closest(".modal").on("hide.bs.modal", function () {
                    return i.hide()
                })
            }), this.config.selector ? this.config = C({}, this.config, {
                trigger: "manual",
                selector: ""
            }) : this._fixTitle()
        }, $t._fixTitle = function () {
            var t = typeof this.element.getAttribute("data-original-title");
            !this.element.getAttribute("title") && "string" == t || (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""), this.element.setAttribute("title", ""))
        }, $t._enter = function (t, e) {
            var i = this.constructor.DATA_KEY;
            (e = e || M(t.currentTarget).data(i)) || (e = new this.constructor(t.currentTarget, this._getDelegateConfig()), M(t.currentTarget).data(i, e)), t && (e._activeTrigger["focusin" === t.type ? K : X] = !0), M(e.getTipElement()).hasClass(Y) || e._hoverState === V ? e._hoverState = V : (clearTimeout(e._timeout), e._hoverState = V, e.config.delay && e.config.delay.show ? e._timeout = setTimeout(function () {
                e._hoverState === V && e.show()
            }, e.config.delay.show) : e.show())
        }, $t._leave = function (t, e) {
            var i = this.constructor.DATA_KEY;
            (e = e || M(t.currentTarget).data(i)) || (e = new this.constructor(t.currentTarget, this._getDelegateConfig()), M(t.currentTarget).data(i, e)), t && (e._activeTrigger["focusout" === t.type ? K : X] = !1), e._isWithActiveTrigger() || (clearTimeout(e._timeout), e._hoverState = "out", e.config.delay && e.config.delay.hide ? e._timeout = setTimeout(function () {
                "out" === e._hoverState && e.hide()
            }, e.config.delay.hide) : e.hide())
        }, $t._isWithActiveTrigger = function () {
            for (var t in this._activeTrigger) if (this._activeTrigger[t]) return !0;
            return !1
        }, $t._getConfig = function (t) {
            return "number" == typeof (t = C({}, this.constructor.Default, M(this.element).data(), t)).delay && (t.delay = {
                show: t.delay,
                hide: t.delay
            }), "number" == typeof t.title && (t.title = t.title.toString()), "number" == typeof t.content && (t.content = t.content.toString()), gt.typeCheckConfig(N, t, this.constructor.DefaultType), t
        }, $t._getDelegateConfig = function () {
            var t = {};
            if (this.config) for (var e in this.config) this.constructor.Default[e] !== this.config[e] && (t[e] = this.config[e]);
            return t
        }, $t._cleanTipClass = function () {
            var t = M(this.getTipElement()), e = t.attr("class").match(R);
            null !== e && 0 < e.length && t.removeClass(e.join(""))
        }, $t._handlePopperPlacementChange = function (t) {
            this._cleanTipClass(), this.addAttachmentClass(this._getAttachment(t.placement))
        }, $t._fixTransition = function () {
            var t = this.getTipElement(), e = this.config.animation;
            null === t.getAttribute("x-placement") && (M(t).removeClass(G), this.config.animation = !1, this.hide(), this.show(), this.config.animation = e)
        }, kt._jQueryInterface = function (i) {
            return this.each(function () {
                var t = M(this).data(H), e = "object" == typeof i && i;
                if ((t || !/dispose|hide/.test(i)) && (t || (t = new kt(this, e), M(this).data(H, t)), "string" == typeof i)) {
                    if (void 0 === t[i]) throw new TypeError('No method named "' + i + '"');
                    t[i]()
                }
            })
        }, _(kt, null, [{
            key: "VERSION", get: function () {
                return "4.0.0"
            }
        }, {
            key: "Default", get: function () {
                return q
            }
        }, {
            key: "NAME", get: function () {
                return N
            }
        }, {
            key: "DATA_KEY", get: function () {
                return H
            }
        }, {
            key: "Event", get: function () {
                return U
            }
        }, {
            key: "EVENT_KEY", get: function () {
                return j
            }
        }, {
            key: "DefaultType", get: function () {
                return W
            }
        }]), Z = kt, M.fn[N] = Z._jQueryInterface, M.fn[N].Constructor = Z, M.fn[N].noConflict = function () {
            return M.fn[N] = B, Z._jQueryInterface
        }, Z),
        St = (E = "popover", I = "." + (k = "bs.popover"), O = ($ = e).fn[E], z = new RegExp("(^|\\s)bs-popover\\S+", "g"), A = C({}, Ct.Default, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }), P = C({}, Ct.DefaultType, {content: "(string|element|function)"}), D = {
            HIDE: "hide" + I,
            HIDDEN: "hidden" + I,
            SHOW: "show" + I,
            SHOWN: "shown" + I,
            INSERTED: "inserted" + I,
            CLICK: "click" + I,
            FOCUSIN: "focusin" + I,
            FOCUSOUT: "focusout" + I,
            MOUSEENTER: "mouseenter" + I,
            MOUSELEAVE: "mouseleave" + I
        }, L = function (t) {
            var e;

            function n() {
                return t.apply(this, arguments) || this
            }

            i = t, (e = n).prototype = Object.create(i.prototype), (e.prototype.constructor = e).__proto__ = i;
            var i = n.prototype;
            return i.isWithContent = function () {
                return this.getTitle() || this._getContent()
            }, i.addAttachmentClass = function (t) {
                $(this.getTipElement()).addClass("bs-popover-" + t)
            }, i.getTipElement = function () {
                return this.tip = this.tip || $(this.config.template)[0], this.tip
            }, i.setContent = function () {
                var t = $(this.getTipElement());
                this.setElementContent(t.find(".popover-header"), this.getTitle());
                var e = this._getContent();
                "function" == typeof e && (e = e.call(this.element)), this.setElementContent(t.find(".popover-body"), e), t.removeClass("fade show")
            }, i._getContent = function () {
                return this.element.getAttribute("data-content") || this.config.content
            }, i._cleanTipClass = function () {
                var t = $(this.getTipElement()), e = t.attr("class").match(z);
                null !== e && 0 < e.length && t.removeClass(e.join(""))
            }, n._jQueryInterface = function (i) {
                return this.each(function () {
                    var t = $(this).data(k), e = "object" == typeof i ? i : null;
                    if ((t || !/destroy|hide/.test(i)) && (t || (t = new n(this, e), $(this).data(k, t)), "string" == typeof i)) {
                        if (void 0 === t[i]) throw new TypeError('No method named "' + i + '"');
                        t[i]()
                    }
                })
            }, _(n, null, [{
                key: "VERSION", get: function () {
                    return "4.0.0"
                }
            }, {
                key: "Default", get: function () {
                    return A
                }
            }, {
                key: "NAME", get: function () {
                    return E
                }
            }, {
                key: "DATA_KEY", get: function () {
                    return k
                }
            }, {
                key: "Event", get: function () {
                    return D
                }
            }, {
                key: "EVENT_KEY", get: function () {
                    return I
                }
            }, {
                key: "DefaultType", get: function () {
                    return P
                }
            }]), n
        }(Ct), $.fn[E] = L._jQueryInterface, $.fn[E].Constructor = L, $.fn[E].noConflict = function () {
            return $.fn[E] = O, L._jQueryInterface
        }, L), Tt = function (s) {
            var t, i = "scrollspy", n = "bs.scrollspy", e = "." + n, o = s.fn[i],
                a = {offset: 10, method: "auto", target: ""},
                r = {offset: "number", method: "string", target: "(string|element)"},
                l = {ACTIVATE: "activate" + e, SCROLL: "scroll" + e, LOAD_DATA_API: "load" + e + ".data-api"}, c = "active",
                d = '[data-spy="scroll"]', u = ".active", h = ".nav, .list-group", p = ".nav-link", f = ".nav-item",
                m = ".list-group-item", g = ".dropdown", v = ".dropdown-item", b = ".dropdown-toggle", y = "position",
                w = ((t = x.prototype).refresh = function () {
                    var e = this, t = this._scrollElement === this._scrollElement.window ? "offset" : y,
                        n = "auto" === this._config.method ? t : this._config.method,
                        o = n === y ? this._getScrollTop() : 0;
                    this._offsets = [], this._targets = [], this._scrollHeight = this._getScrollHeight(), s.makeArray(s(this._selector)).map(function (t) {
                        var e, i = gt.getSelectorFromElement(t);
                        if (e = i ? s(i)[0] : e) {
                            t = e.getBoundingClientRect();
                            if (t.width || t.height) return [s(e)[n]().top + o, i]
                        }
                        return null
                    }).filter(function (t) {
                        return t
                    }).sort(function (t, e) {
                        return t[0] - e[0]
                    }).forEach(function (t) {
                        e._offsets.push(t[0]), e._targets.push(t[1])
                    })
                }, t.dispose = function () {
                    s.removeData(this._element, n), s(this._scrollElement).off(e), this._element = null, this._scrollElement = null, this._config = null, this._selector = null, this._offsets = null, this._targets = null, this._activeTarget = null, this._scrollHeight = null
                }, t._getConfig = function (t) {
                    var e;
                    return "string" != typeof (t = C({}, a, t)).target && ((e = s(t.target).attr("id")) || (e = gt.getUID(i), s(t.target).attr("id", e)), t.target = "#" + e), gt.typeCheckConfig(i, t, r), t
                }, t._getScrollTop = function () {
                    return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop
                }, t._getScrollHeight = function () {
                    return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                }, t._getOffsetHeight = function () {
                    return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height
                }, t._process = function () {
                    var t = this._getScrollTop() + this._config.offset, e = this._getScrollHeight(),
                        i = this._config.offset + e - this._getOffsetHeight();
                    if (this._scrollHeight !== e && this.refresh(), i <= t) {
                        i = this._targets[this._targets.length - 1];
                        this._activeTarget !== i && this._activate(i)
                    } else {
                        if (this._activeTarget && t < this._offsets[0] && 0 < this._offsets[0]) return this._activeTarget = null, void this._clear();
                        for (var n = this._offsets.length; n--;) this._activeTarget !== this._targets[n] && t >= this._offsets[n] && (void 0 === this._offsets[n + 1] || t < this._offsets[n + 1]) && this._activate(this._targets[n])
                    }
                }, t._activate = function (e) {
                    this._activeTarget = e, this._clear();
                    var t = (t = this._selector.split(",")).map(function (t) {
                        return t + '[data-target="' + e + '"],' + t + '[href="' + e + '"]'
                    }), t = s(t.join(","));
                    t.hasClass("dropdown-item") ? (t.closest(g).find(b).addClass(c), t.addClass(c)) : (t.addClass(c), t.parents(h).prev(p + ", " + m).addClass(c), t.parents(h).prev(f).children(p).addClass(c)), s(this._scrollElement).trigger(l.ACTIVATE, {relatedTarget: e})
                }, t._clear = function () {
                    s(this._selector).filter(u).removeClass(c)
                }, x._jQueryInterface = function (e) {
                    return this.each(function () {
                        var t = s(this).data(n);
                        if (t || (t = new x(this, "object" == typeof e && e), s(this).data(n, t)), "string" == typeof e) {
                            if (void 0 === t[e]) throw new TypeError('No method named "' + e + '"');
                            t[e]()
                        }
                    })
                }, _(x, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0"
                    }
                }, {
                    key: "Default", get: function () {
                        return a
                    }
                }]), x);

            function x(t, e) {
                var i = this;
                this._element = t, this._scrollElement = "BODY" === t.tagName ? window : t, this._config = this._getConfig(e), this._selector = this._config.target + " " + p + "," + this._config.target + " " + m + "," + this._config.target + " " + v, this._offsets = [], this._targets = [], this._activeTarget = null, this._scrollHeight = 0, s(this._scrollElement).on(l.SCROLL, function (t) {
                    return i._process(t)
                }), this.refresh(), this._process()
            }

            return s(window).on(l.LOAD_DATA_API, function () {
                for (var t = s.makeArray(s(d)), e = t.length; e--;) {
                    var i = s(t[e]);
                    w._jQueryInterface.call(i, i.data())
                }
            }), s.fn[i] = w._jQueryInterface, s.fn[i].Constructor = w, s.fn[i].noConflict = function () {
                return s.fn[i] = o, w._jQueryInterface
            }, w
        }(e), $t = ($t = "." + (v = "bs.tab"), b = (g = e).fn.tab, y = {
            HIDE: "hide" + $t,
            HIDDEN: "hidden" + $t,
            SHOW: "show" + $t,
            SHOWN: "shown" + $t,
            CLICK_DATA_API: "click.bs.tab.data-api"
        }, w = "active", x = ".active", S = "> li > .active", ($t = Et.prototype).show = function () {
            var t, e, i, n, o, s, a = this;
            this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && g(this._element).hasClass(w) || g(this._element).hasClass("disabled") || (s = g(this._element).closest(".nav, .list-group")[0], e = gt.getSelectorFromElement(this._element), s && (o = "UL" === s.nodeName ? S : x, i = (i = g.makeArray(g(s).find(o)))[i.length - 1]), n = g.Event(y.HIDE, {relatedTarget: this._element}), o = g.Event(y.SHOW, {relatedTarget: i}), i && g(i).trigger(n), g(this._element).trigger(o), o.isDefaultPrevented() || n.isDefaultPrevented() || (e && (t = g(e)[0]), this._activate(this._element, s), s = function () {
                var t = g.Event(y.HIDDEN, {relatedTarget: a._element}), e = g.Event(y.SHOWN, {relatedTarget: i});
                g(i).trigger(t), g(a._element).trigger(e)
            }, t ? this._activate(t, t.parentNode, s) : s()))
        }, $t.dispose = function () {
            g.removeData(this._element, v), this._element = null
        }, $t._activate = function (t, e, i) {
            function n() {
                return o._transitionComplete(t, s, i)
            }

            var o = this, s = ("UL" === e.nodeName ? g(e).find(S) : g(e).children(x))[0],
                e = i && gt.supportsTransitionEnd() && s && g(s).hasClass("fade");
            s && e ? g(s).one(gt.TRANSITION_END, n).emulateTransitionEnd(150) : n()
        }, $t._transitionComplete = function (t, e, i) {
            var n;
            e && (g(e).removeClass("show " + w), (n = g(e.parentNode).find("> .dropdown-menu .active")[0]) && g(n).removeClass(w), "tab" === e.getAttribute("role") && e.setAttribute("aria-selected", !1)), g(t).addClass(w), "tab" === t.getAttribute("role") && t.setAttribute("aria-selected", !0), gt.reflow(t), g(t).addClass("show"), t.parentNode && g(t.parentNode).hasClass("dropdown-menu") && ((e = g(t).closest(".dropdown")[0]) && g(e).find(".dropdown-toggle").addClass(w), t.setAttribute("aria-expanded", !0)), i && i()
        }, Et._jQueryInterface = function (i) {
            return this.each(function () {
                var t = g(this), e = t.data(v);
                if (e || (e = new Et(this), t.data(v, e)), "string" == typeof i) {
                    if (void 0 === e[i]) throw new TypeError('No method named "' + i + '"');
                    e[i]()
                }
            })
        }, _(Et, null, [{
            key: "VERSION", get: function () {
                return "4.0.0"
            }
        }]), T = Et, g(document).on(y.CLICK_DATA_API, '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]', function (t) {
            t.preventDefault(), T._jQueryInterface.call(g(this), "show")
        }), g.fn.tab = T._jQueryInterface, g.fn.tab.Constructor = T, g.fn.tab.noConflict = function () {
            return g.fn.tab = b, T._jQueryInterface
        }, T);

    function Et(t) {
        this._element = t
    }

    function kt(t, e) {
        if (void 0 === o) throw new TypeError("Bootstrap tooltips require Popper.js (https://popper.js.org)");
        this._isEnabled = !0, this._timeout = 0, this._hoverState = "", this._activeTrigger = {}, this._popper = null, this.element = t, this.config = this._getConfig(e), this.tip = null, this._setListeners()
    }

    function It(t, e) {
        this._element = t, this._popper = null, this._config = this._getConfig(e), this._menu = this._getMenuElement(), this._inNavbar = this._detectNavbar(), this._addEventListeners()
    }

    function Ot(t) {
        this._element = t
    }

    function zt(t) {
        this._element = t
    }

    !function (t) {
        if (void 0 === t) throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");
        t = t.fn.jquery.split(" ")[0].split(".");
        if (t[0] < 2 && t[1] < 9 || 1 === t[0] && 9 === t[1] && t[2] < 1 || 4 <= t[0]) throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")
    }(e), t.Util = gt, t.Alert = vt, t.Button = bt, t.Carousel = yt, t.Collapse = wt, t.Dropdown = xt, t.Modal = _t, t.Popover = St, t.Scrollspy = Tt, t.Tab = $t, t.Tooltip = Ct, Object.defineProperty(t, "__esModule", {value: !0})
}), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : (t = t || self).Swiper = e()
}(this, function () {
    "use strict";

    function t(t, e) {
        for (var i = 0; i < e.length; i++) {
            var n = e[i];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }

    function e() {
        return (e = Object.assign || function (t) {
            for (var e = 1; e < arguments.length; e++) {
                var i, n = arguments[e];
                for (i in n) Object.prototype.hasOwnProperty.call(n, i) && (t[i] = n[i])
            }
            return t
        }).apply(this, arguments)
    }

    function n(t) {
        return null !== t && "object" == typeof t && "constructor" in t && t.constructor === Object
    }

    function o(e, i) {
        void 0 === e && (e = {}), void 0 === i && (i = {}), Object.keys(i).forEach(function (t) {
            void 0 === e[t] ? e[t] = i[t] : n(i[t]) && n(e[t]) && 0 < Object.keys(i[t]).length && o(e[t], i[t])
        })
    }

    var i = {
        body: {}, addEventListener: function () {
        }, removeEventListener: function () {
        }, activeElement: {
            blur: function () {
            }, nodeName: ""
        }, querySelector: function () {
            return null
        }, querySelectorAll: function () {
            return []
        }, getElementById: function () {
            return null
        }, createEvent: function () {
            return {
                initEvent: function () {
                }
            }
        }, createElement: function () {
            return {
                children: [], childNodes: [], style: {}, setAttribute: function () {
                }, getElementsByTagName: function () {
                    return []
                }
            }
        }, createElementNS: function () {
            return {}
        }, importNode: function () {
            return null
        }, location: {hash: "", host: "", hostname: "", href: "", origin: "", pathname: "", protocol: "", search: ""}
    };

    function v() {
        var t = "undefined" != typeof document ? document : {};
        return o(t, i), t
    }

    var s = {
        document: i,
        navigator: {userAgent: ""},
        location: {hash: "", host: "", hostname: "", href: "", origin: "", pathname: "", protocol: "", search: ""},
        history: {
            replaceState: function () {
            }, pushState: function () {
            }, go: function () {
            }, back: function () {
            }
        },
        CustomEvent: function () {
            return this
        },
        addEventListener: function () {
        },
        removeEventListener: function () {
        },
        getComputedStyle: function () {
            return {
                getPropertyValue: function () {
                    return ""
                }
            }
        },
        Image: function () {
        },
        Date: function () {
        },
        screen: {},
        setTimeout: function () {
        },
        clearTimeout: function () {
        },
        matchMedia: function () {
            return {}
        },
        requestAnimationFrame: function (t) {
            return "undefined" == typeof setTimeout ? (t(), null) : setTimeout(t, 0)
        },
        cancelAnimationFrame: function (t) {
            "undefined" != typeof setTimeout && clearTimeout(t)
        }
    };

    function Y() {
        var t = "undefined" != typeof window ? window : {};
        return o(t, s), t
    }

    function a(t) {
        return (a = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
            return t.__proto__ || Object.getPrototypeOf(t)
        })(t)
    }

    function r(t, e) {
        return (r = Object.setPrototypeOf || function (t, e) {
            return t.__proto__ = e, t
        })(t, e)
    }

    function l(t, e, i) {
        return (l = function () {
            if ("undefined" != typeof Reflect && Reflect.construct && !Reflect.construct.sham) {
                if ("function" == typeof Proxy) return 1;
                try {
                    return Date.prototype.toString.call(Reflect.construct(Date, [], function () {
                    })), 1
                } catch (t) {
                    return
                }
            }
        }() ? Reflect.construct : function (t, e, i) {
            var n = [null];
            n.push.apply(n, e);
            n = new (Function.bind.apply(t, n));
            return i && r(n, i.prototype), n
        }).apply(null, arguments)
    }

    function c(t) {
        var i = "function" == typeof Map ? new Map : void 0;
        return function (t) {
            if (null === t || -1 === Function.toString.call(t).indexOf("[native code]")) return t;
            if ("function" != typeof t) throw new TypeError("Super expression must either be null or a function");
            if (void 0 !== i) {
                if (i.has(t)) return i.get(t);
                i.set(t, e)
            }

            function e() {
                return l(t, arguments, a(this).constructor)
            }

            return e.prototype = Object.create(t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }), r(e, t)
        }(t)
    }

    var d,
        u = (d = c(Array), P = d, (D = h).prototype = Object.create(P.prototype), (D.prototype.constructor = D).__proto__ = P, h);

    function h(t) {
        var e = d.call.apply(d, [this].concat(t)) || this, t = function (t) {
            if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return t
        }(e), i = t.__proto__;
        return Object.defineProperty(t, "__proto__", {
            get: function () {
                return i
            }, set: function (t) {
                i.__proto__ = t
            }
        }), e
    }

    function p(t) {
        var e = [];
        return (t = void 0 === t ? [] : t).forEach(function (t) {
            Array.isArray(t) ? e.push.apply(e, p(t)) : e.push(t)
        }), e
    }

    function f(t, e) {
        return Array.prototype.filter.call(t, e)
    }

    function C(t, o) {
        var e = Y(), s = v(), i = [];
        if (!o && t instanceof u) return t;
        if (!t) return new u(i);
        if ("string" == typeof t) {
            var n = t.trim();
            if (0 <= n.indexOf("<") && 0 <= n.indexOf(">")) {
                var a = "div";
                0 === n.indexOf("<li") && (a = "ul"), 0 === n.indexOf("<tr") && (a = "tbody"), 0 !== n.indexOf("<td") && 0 !== n.indexOf("<th") || (a = "tr"), 0 === n.indexOf("<tbody") && (a = "table"), 0 === n.indexOf("<option") && (a = "select");
                var r = s.createElement(a);
                r.innerHTML = n;
                for (var l = 0; l < r.childNodes.length; l += 1) i.push(r.childNodes[l])
            } else i = function (t) {
                if ("string" != typeof t) return [t];
                for (var e = [], i = (o || s).querySelectorAll(t), n = 0; n < i.length; n += 1) e.push(i[n]);
                return e
            }(t.trim())
        } else if (t.nodeType || t === e || t === s) i.push(t); else if (Array.isArray(t)) {
            if (t instanceof u) return t;
            i = t
        }
        return new u(function (t) {
            for (var e = [], i = 0; i < t.length; i += 1) -1 === e.indexOf(t[i]) && e.push(t[i]);
            return e
        }(i))
    }

    C.fn = u.prototype;
    var m, x, _, g = {
        addClass: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = p(e.map(function (t) {
                return t.split(" ")
            }));
            return this.forEach(function (t) {
                (t = t.classList).add.apply(t, n)
            }), this
        }, removeClass: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = p(e.map(function (t) {
                return t.split(" ")
            }));
            return this.forEach(function (t) {
                (t = t.classList).remove.apply(t, n)
            }), this
        }, hasClass: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = p(e.map(function (t) {
                return t.split(" ")
            }));
            return 0 < f(this, function (e) {
                return 0 < n.filter(function (t) {
                    return e.classList.contains(t)
                }).length
            }).length
        }, toggleClass: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = p(e.map(function (t) {
                return t.split(" ")
            }));
            this.forEach(function (e) {
                n.forEach(function (t) {
                    e.classList.toggle(t)
                })
            })
        }, attr: function (t, e) {
            if (1 === arguments.length && "string" == typeof t) return this[0] ? this[0].getAttribute(t) : void 0;
            for (var i = 0; i < this.length; i += 1) if (2 === arguments.length) this[i].setAttribute(t, e); else for (var n in t) this[i][n] = t[n], this[i].setAttribute(n, t[n]);
            return this
        }, removeAttr: function (t) {
            for (var e = 0; e < this.length; e += 1) this[e].removeAttribute(t);
            return this
        }, transform: function (t) {
            for (var e = 0; e < this.length; e += 1) this[e].style.transform = t;
            return this
        }, transition: function (t) {
            for (var e = 0; e < this.length; e += 1) this[e].style.transition = "string" != typeof t ? t + "ms" : t;
            return this
        }, on: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = e[0], s = e[1], a = e[2], o = e[3];

            function r(t) {
                var e = t.target;
                if (e) {
                    var i = t.target.dom7EventData || [];
                    if (i.indexOf(t) < 0 && i.unshift(t), C(e).is(s)) a.apply(e, i); else for (var n = C(e).parents(), o = 0; o < n.length; o += 1) C(n[o]).is(s) && a.apply(n[o], i)
                }
            }

            function l(t) {
                var e = t && t.target && t.target.dom7EventData || [];
                e.indexOf(t) < 0 && e.unshift(t), a.apply(this, e)
            }

            "function" == typeof e[1] && (n = e[0], a = e[1], o = e[2], s = void 0);
            for (var c, o = o || !1, d = n.split(" "), u = 0; u < this.length; u += 1) {
                var h = this[u];
                if (s) for (c = 0; c < d.length; c += 1) {
                    var p = d[c];
                    h.dom7LiveListeners || (h.dom7LiveListeners = {}), h.dom7LiveListeners[p] || (h.dom7LiveListeners[p] = []), h.dom7LiveListeners[p].push({
                        listener: a,
                        proxyListener: r
                    }), h.addEventListener(p, r, o)
                } else for (c = 0; c < d.length; c += 1) {
                    var f = d[c];
                    h.dom7Listeners || (h.dom7Listeners = {}), h.dom7Listeners[f] || (h.dom7Listeners[f] = []), h.dom7Listeners[f].push({
                        listener: a,
                        proxyListener: l
                    }), h.addEventListener(f, l, o)
                }
            }
            return this
        }, off: function () {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = e[0], o = e[1], s = e[2], a = e[3];
            "function" == typeof e[1] && (n = e[0], s = e[1], a = e[2], o = void 0);
            for (var a = a || !1, r = n.split(" "), l = 0; l < r.length; l += 1) for (var c = r[l], d = 0; d < this.length; d += 1) {
                var u = this[d], h = void 0;
                if (!o && u.dom7Listeners ? h = u.dom7Listeners[c] : o && u.dom7LiveListeners && (h = u.dom7LiveListeners[c]), h && h.length) for (var p = h.length - 1; 0 <= p; --p) {
                    var f = h[p];
                    !(s && f.listener === s || s && f.listener && f.listener.dom7proxy && f.listener.dom7proxy === s) && s || (u.removeEventListener(c, f.proxyListener, a), h.splice(p, 1))
                }
            }
            return this
        }, trigger: function () {
            for (var t = Y(), e = arguments.length, i = new Array(e), n = 0; n < e; n++) i[n] = arguments[n];
            for (var o = i[0].split(" "), s = i[1], a = 0; a < o.length; a += 1) for (var r = o[a], l = 0; l < this.length; l += 1) {
                var c, d = this[l];
                t.CustomEvent && (c = new t.CustomEvent(r, {
                    detail: s,
                    bubbles: !0,
                    cancelable: !0
                }), d.dom7EventData = i.filter(function (t, e) {
                    return 0 < e
                }), d.dispatchEvent(c), d.dom7EventData = [], delete d.dom7EventData)
            }
            return this
        }, transitionEnd: function (i) {
            var n = this;
            return i && n.on("transitionend", function t(e) {
                e.target === this && (i.call(this, e), n.off("transitionend", t))
            }), this
        }, outerWidth: function (t) {
            if (0 < this.length) {
                if (t) {
                    t = this.styles();
                    return this[0].offsetWidth + parseFloat(t.getPropertyValue("margin-right")) + parseFloat(t.getPropertyValue("margin-left"))
                }
                return this[0].offsetWidth
            }
            return null
        }, outerHeight: function (t) {
            if (0 < this.length) {
                if (t) {
                    t = this.styles();
                    return this[0].offsetHeight + parseFloat(t.getPropertyValue("margin-top")) + parseFloat(t.getPropertyValue("margin-bottom"))
                }
                return this[0].offsetHeight
            }
            return null
        }, styles: function () {
            var t = Y();
            return this[0] ? t.getComputedStyle(this[0], null) : {}
        }, offset: function () {
            if (0 < this.length) {
                var t = Y(), e = v(), i = this[0], n = i.getBoundingClientRect(), o = e.body,
                    s = i.clientTop || o.clientTop || 0, e = i.clientLeft || o.clientLeft || 0,
                    o = i === t ? t.scrollY : i.scrollTop, i = i === t ? t.scrollX : i.scrollLeft;
                return {top: n.top + o - s, left: n.left + i - e}
            }
            return null
        }, css: function (t, e) {
            var i, n = Y();
            if (1 === arguments.length) {
                if ("string" != typeof t) {
                    for (i = 0; i < this.length; i += 1) for (var o in t) this[i].style[o] = t[o];
                    return this
                }
                if (this[0]) return n.getComputedStyle(this[0], null).getPropertyValue(t)
            }
            if (2 !== arguments.length || "string" != typeof t) return this;
            for (i = 0; i < this.length; i += 1) this[i].style[t] = e;
            return this
        }, each: function (i) {
            return i && this.forEach(function (t, e) {
                i.apply(t, [t, e])
            }), this
        }, html: function (t) {
            if (void 0 === t) return this[0] ? this[0].innerHTML : null;
            for (var e = 0; e < this.length; e += 1) this[e].innerHTML = t;
            return this
        }, text: function (t) {
            if (void 0 === t) return this[0] ? this[0].textContent.trim() : null;
            for (var e = 0; e < this.length; e += 1) this[e].textContent = t;
            return this
        }, is: function (t) {
            var e, i, n = Y(), o = v(), s = this[0];
            if (!s || void 0 === t) return !1;
            if ("string" == typeof t) {
                if (s.matches) return s.matches(t);
                if (s.webkitMatchesSelector) return s.webkitMatchesSelector(t);
                if (s.msMatchesSelector) return s.msMatchesSelector(t);
                for (e = C(t), i = 0; i < e.length; i += 1) if (e[i] === s) return !0;
                return !1
            }
            if (t === o) return s === o;
            if (t === n) return s === n;
            if (t.nodeType || t instanceof u) {
                for (e = t.nodeType ? [t] : t, i = 0; i < e.length; i += 1) if (e[i] === s) return !0;
                return !1
            }
            return !1
        }, index: function () {
            var t, e = this[0];
            if (e) {
                for (t = 0; null !== (e = e.previousSibling);) 1 === e.nodeType && (t += 1);
                return t
            }
        }, eq: function (t) {
            if (void 0 === t) return this;
            var e = this.length;
            if (e - 1 < t) return C([]);
            if (t < 0) {
                e = e + t;
                return C(e < 0 ? [] : [this[e]])
            }
            return C([this[t]])
        }, append: function () {
            for (var t = v(), e = 0; e < arguments.length; e += 1) for (var i = e < 0 || arguments.length <= e ? void 0 : arguments[e], n = 0; n < this.length; n += 1) if ("string" == typeof i) {
                var o = t.createElement("div");
                for (o.innerHTML = i; o.firstChild;) this[n].appendChild(o.firstChild)
            } else if (i instanceof u) for (var s = 0; s < i.length; s += 1) this[n].appendChild(i[s]); else this[n].appendChild(i);
            return this
        }, prepend: function (t) {
            for (var e, i = v(), n = 0; n < this.length; n += 1) if ("string" == typeof t) {
                var o = i.createElement("div");
                for (o.innerHTML = t, e = o.childNodes.length - 1; 0 <= e; --e) this[n].insertBefore(o.childNodes[e], this[n].childNodes[0])
            } else if (t instanceof u) for (e = 0; e < t.length; e += 1) this[n].insertBefore(t[e], this[n].childNodes[0]); else this[n].insertBefore(t, this[n].childNodes[0]);
            return this
        }, next: function (t) {
            return 0 < this.length ? t ? this[0].nextElementSibling && C(this[0].nextElementSibling).is(t) ? C([this[0].nextElementSibling]) : C([]) : this[0].nextElementSibling ? C([this[0].nextElementSibling]) : C([]) : C([])
        }, nextAll: function (t) {
            var e = [], i = this[0];
            if (!i) return C([]);
            for (; i.nextElementSibling;) {
                var n = i.nextElementSibling;
                t && !C(n).is(t) || e.push(n), i = n
            }
            return C(e)
        }, prev: function (t) {
            if (0 < this.length) {
                var e = this[0];
                return t ? e.previousElementSibling && C(e.previousElementSibling).is(t) ? C([e.previousElementSibling]) : C([]) : e.previousElementSibling ? C([e.previousElementSibling]) : C([])
            }
            return C([])
        }, prevAll: function (t) {
            var e = [], i = this[0];
            if (!i) return C([]);
            for (; i.previousElementSibling;) {
                var n = i.previousElementSibling;
                t && !C(n).is(t) || e.push(n), i = n
            }
            return C(e)
        }, parent: function (t) {
            for (var e = [], i = 0; i < this.length; i += 1) null === this[i].parentNode || t && !C(this[i].parentNode).is(t) || e.push(this[i].parentNode);
            return C(e)
        }, parents: function (t) {
            for (var e = [], i = 0; i < this.length; i += 1) for (var n = this[i].parentNode; n;) t && !C(n).is(t) || e.push(n), n = n.parentNode;
            return C(e)
        }, closest: function (t) {
            var e = this;
            return void 0 === t ? C([]) : e = !e.is(t) ? e.parents(t).eq(0) : e
        }, find: function (t) {
            for (var e = [], i = 0; i < this.length; i += 1) for (var n = this[i].querySelectorAll(t), o = 0; o < n.length; o += 1) e.push(n[o]);
            return C(e)
        }, children: function (t) {
            for (var e = [], i = 0; i < this.length; i += 1) for (var n = this[i].children, o = 0; o < n.length; o += 1) t && !C(n[o]).is(t) || e.push(n[o]);
            return C(e)
        }, filter: function (t) {
            return C(f(this, t))
        }, remove: function () {
            for (var t = 0; t < this.length; t += 1) this[t].parentNode && this[t].parentNode.removeChild(this[t]);
            return this
        }
    };

    function S(t, e) {
        return void 0 === e && (e = 0), setTimeout(t, e)
    }

    function T() {
        return Date.now()
    }

    function b(t, e) {
        void 0 === e && (e = "x");
        var i, n, o, s = Y(), t = s.getComputedStyle(t, null);
        return s.WebKitCSSMatrix ? (6 < (n = t.transform || t.webkitTransform).split(",").length && (n = n.split(", ").map(function (t) {
            return t.replace(",", ".")
        }).join(", ")), o = new s.WebKitCSSMatrix("none" === n ? "" : n)) : i = (o = t.MozTransform || t.OTransform || t.MsTransform || t.msTransform || t.transform || t.getPropertyValue("transform").replace("translate(", "matrix(1, 0, 0, 1,")).toString().split(","), "x" === e && (n = s.WebKitCSSMatrix ? o.m41 : 16 === i.length ? parseFloat(i[12]) : parseFloat(i[4])), (n = "y" === e ? s.WebKitCSSMatrix ? o.m42 : 16 === i.length ? parseFloat(i[13]) : parseFloat(i[5]) : n) || 0
    }

    function y(t) {
        return "object" == typeof t && null !== t && t.constructor && t.constructor === Object
    }

    function X(t) {
        for (var e = Object(arguments.length <= 0 ? void 0 : t), i = 1; i < arguments.length; i += 1) {
            var n = i < 0 || arguments.length <= i ? void 0 : arguments[i];
            if (null != n) for (var o = Object.keys(Object(n)), s = 0, a = o.length; s < a; s += 1) {
                var r = o[s], l = Object.getOwnPropertyDescriptor(n, r);
                void 0 !== l && l.enumerable && (y(e[r]) && y(n[r]) ? X(e[r], n[r]) : !y(e[r]) && y(n[r]) ? (e[r] = {}, X(e[r], n[r])) : e[r] = n[r])
            }
        }
        return e
    }

    function w(i, n) {
        Object.keys(n).forEach(function (e) {
            y(n[e]) && Object.keys(n[e]).forEach(function (t) {
                "function" == typeof n[e][t] && (n[e][t] = n[e][t].bind(i))
            }), i[e] = n[e]
        })
    }

    function $() {
        return m || (i = Y(), t = v(), m = {
            touch: !!("ontouchstart" in i || i.DocumentTouch && t instanceof i.DocumentTouch),
            pointerEvents: !!i.PointerEvent && "maxTouchPoints" in i.navigator && 0 <= i.navigator.maxTouchPoints,
            observer: "MutationObserver" in i || "WebkitMutationObserver" in i,
            passiveListener: function () {
                var t = !1;
                try {
                    var e = Object.defineProperty({}, "passive", {
                        get: function () {
                            t = !0
                        }
                    });
                    i.addEventListener("testPassiveListener", null, e)
                } catch (t) {
                }
                return t
            }(),
            gestures: "ongesturestart" in i
        }), m;
        var i, t
    }

    Object.keys(g).forEach(function (t) {
        C.fn[t] = g[t]
    });
    var E = {
        name: "resize", create: function () {
            var t = this;
            X(t, {
                resize: {
                    resizeHandler: function () {
                        t && !t.destroyed && t.initialized && (t.emit("beforeResize"), t.emit("resize"))
                    }, orientationChangeHandler: function () {
                        t && !t.destroyed && t.initialized && t.emit("orientationchange")
                    }
                }
            })
        }, on: {
            init: function (t) {
                var e = Y();
                e.addEventListener("resize", t.resize.resizeHandler), e.addEventListener("orientationchange", t.resize.orientationChangeHandler)
            }, destroy: function (t) {
                var e = Y();
                e.removeEventListener("resize", t.resize.resizeHandler), e.removeEventListener("orientationchange", t.resize.orientationChangeHandler)
            }
        }
    }, k = {
        attach: function (t, e) {
            void 0 === e && (e = {});
            var i = Y(), n = this, o = new (i.MutationObserver || i.WebkitMutationObserver)(function (t) {
                var e;
                1 !== t.length ? (e = function () {
                    n.emit("observerUpdate", t[0])
                }, i.requestAnimationFrame ? i.requestAnimationFrame(e) : i.setTimeout(e, 0)) : n.emit("observerUpdate", t[0])
            });
            o.observe(t, {
                attributes: void 0 === e.attributes || e.attributes,
                childList: void 0 === e.childList || e.childList,
                characterData: void 0 === e.characterData || e.characterData
            }), n.observer.observers.push(o)
        }, init: function () {
            if (this.support.observer && this.params.observer) {
                if (this.params.observeParents) for (var t = this.$el.parents(), e = 0; e < t.length; e += 1) this.observer.attach(t[e]);
                this.observer.attach(this.$el[0], {childList: this.params.observeSlideChildren}), this.observer.attach(this.$wrapperEl[0], {attributes: !1})
            }
        }, destroy: function () {
            this.observer.observers.forEach(function (t) {
                t.disconnect()
            }), this.observer.observers = []
        }
    }, I = {
        name: "observer",
        params: {observer: !1, observeParents: !1, observeSlideChildren: !1},
        create: function () {
            w(this, {observer: e(e({}, k), {}, {observers: []})})
        },
        on: {
            init: function (t) {
                t.observer.init()
            }, destroy: function (t) {
                t.observer.destroy()
            }
        }
    };

    function O() {
        var t, e, i = this.params, n = this.el;
        n && 0 === n.offsetWidth || (i.breakpoints && this.setBreakpoint(), t = this.allowSlideNext, e = this.allowSlidePrev, n = this.snapGrid, this.allowSlideNext = !0, this.allowSlidePrev = !0, this.updateSize(), this.updateSlides(), this.updateSlidesClasses(), ("auto" === i.slidesPerView || 1 < i.slidesPerView) && this.isEnd && !this.isBeginning && !this.params.centeredSlides ? this.slideTo(this.slides.length - 1, 0, !1, !0) : this.slideTo(this.activeIndex, 0, !1, !0), this.autoplay && this.autoplay.running && this.autoplay.paused && this.autoplay.run(), this.allowSlidePrev = e, this.allowSlideNext = t, this.params.watchOverflow && n !== this.snapGrid && this.checkOverflow())
    }

    var z = !1;

    function A() {
    }

    var P, D, L = {
        init: !0,
        direction: "horizontal",
        touchEventsTarget: "container",
        initialSlide: 0,
        speed: 300,
        cssMode: !1,
        updateOnWindowResize: !0,
        width: null,
        height: null,
        preventInteractionOnTransition: !1,
        userAgent: null,
        url: null,
        edgeSwipeDetection: !1,
        edgeSwipeThreshold: 20,
        freeMode: !1,
        freeModeMomentum: !0,
        freeModeMomentumRatio: 1,
        freeModeMomentumBounce: !0,
        freeModeMomentumBounceRatio: 1,
        freeModeMomentumVelocityRatio: 1,
        freeModeSticky: !1,
        freeModeMinimumVelocity: .02,
        autoHeight: !1,
        setWrapperSize: !1,
        virtualTranslate: !1,
        effect: "slide",
        breakpoints: void 0,
        spaceBetween: 0,
        slidesPerView: 1,
        slidesPerColumn: 1,
        slidesPerColumnFill: "column",
        slidesPerGroup: 1,
        slidesPerGroupSkip: 0,
        centeredSlides: !1,
        centeredSlidesBounds: !1,
        slidesOffsetBefore: 0,
        slidesOffsetAfter: 0,
        normalizeSlideIndex: !0,
        centerInsufficientSlides: !1,
        watchOverflow: !1,
        roundLengths: !1,
        touchRatio: 1,
        touchAngle: 45,
        simulateTouch: !0,
        shortSwipes: !0,
        longSwipes: !0,
        longSwipesRatio: .5,
        longSwipesMs: 300,
        followFinger: !0,
        allowTouchMove: !0,
        threshold: 0,
        touchMoveStopPropagation: !1,
        touchStartPreventDefault: !0,
        touchStartForcePreventDefault: !1,
        touchReleaseOnEdges: !1,
        uniqueNavElements: !0,
        resistance: !0,
        resistanceRatio: .85,
        watchSlidesProgress: !1,
        watchSlidesVisibility: !1,
        grabCursor: !1,
        preventClicks: !0,
        preventClicksPropagation: !0,
        slideToClickedSlide: !1,
        preloadImages: !0,
        updateOnImagesReady: !0,
        loop: !1,
        loopAdditionalSlides: 0,
        loopedSlides: null,
        loopFillGroupWithBlank: !1,
        loopPreventsSlide: !0,
        allowSlidePrev: !0,
        allowSlideNext: !0,
        swipeHandler: null,
        noSwiping: !0,
        noSwipingClass: "swiper-no-swiping",
        noSwipingSelector: null,
        passiveListeners: !0,
        containerModifierClass: "swiper-container-",
        slideClass: "swiper-slide",
        slideBlankClass: "swiper-slide-invisible-blank",
        slideActiveClass: "swiper-slide-active",
        slideDuplicateActiveClass: "swiper-slide-duplicate-active",
        slideVisibleClass: "swiper-slide-visible",
        slideDuplicateClass: "swiper-slide-duplicate",
        slideNextClass: "swiper-slide-next",
        slideDuplicateNextClass: "swiper-slide-duplicate-next",
        slidePrevClass: "swiper-slide-prev",
        slideDuplicatePrevClass: "swiper-slide-duplicate-prev",
        wrapperClass: "swiper-wrapper",
        runCallbacksOnInit: !0,
        _emitClasses: !1
    }, M = {
        modular: {
            useParams: function (e) {
                var i = this;
                i.modules && Object.keys(i.modules).forEach(function (t) {
                    t = i.modules[t];
                    t.params && X(e, t.params)
                })
            }, useModules: function (i) {
                void 0 === i && (i = {});
                var n = this;
                n.modules && Object.keys(n.modules).forEach(function (t) {
                    var e = n.modules[t], t = i[t] || {};
                    e.on && n.on && Object.keys(e.on).forEach(function (t) {
                        n.on(t, e.on[t])
                    }), e.create && e.create.bind(n)(t)
                })
            }
        }, eventsEmitter: {
            on: function (t, e, i) {
                var n = this;
                if ("function" != typeof e) return n;
                var o = i ? "unshift" : "push";
                return t.split(" ").forEach(function (t) {
                    n.eventsListeners[t] || (n.eventsListeners[t] = []), n.eventsListeners[t][o](e)
                }), n
            }, once: function (n, o, t) {
                var s = this;
                return "function" != typeof o ? s : (a.__emitterProxy = o, s.on(n, a, t));

                function a() {
                    s.off(n, a), a.__emitterProxy && delete a.__emitterProxy;
                    for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
                    o.apply(s, e)
                }
            }, onAny: function (t, e) {
                if ("function" != typeof t) return this;
                e = e ? "unshift" : "push";
                return this.eventsAnyListeners.indexOf(t) < 0 && this.eventsAnyListeners[e](t), this
            }, offAny: function (t) {
                if (!this.eventsAnyListeners) return this;
                t = this.eventsAnyListeners.indexOf(t);
                return 0 <= t && this.eventsAnyListeners.splice(t, 1), this
            }, off: function (t, n) {
                var o = this;
                return o.eventsListeners && t.split(" ").forEach(function (i) {
                    void 0 === n ? o.eventsListeners[i] = [] : o.eventsListeners[i] && o.eventsListeners[i].forEach(function (t, e) {
                        (t === n || t.__emitterProxy && t.__emitterProxy === n) && o.eventsListeners[i].splice(e, 1)
                    })
                }), o
            }, emit: function () {
                var t, n, o, s = this;
                if (!s.eventsListeners) return s;
                for (var e = arguments.length, i = new Array(e), a = 0; a < e; a++) i[a] = arguments[a];
                return o = "string" == typeof i[0] || Array.isArray(i[0]) ? (t = i[0], n = i.slice(1, i.length), s) : (t = i[0].events, n = i[0].data, i[0].context || s), n.unshift(o), (Array.isArray(t) ? t : t.split(" ")).forEach(function (e) {
                    var i;
                    s.eventsAnyListeners && s.eventsAnyListeners.length && s.eventsAnyListeners.forEach(function (t) {
                        t.apply(o, [e].concat(n))
                    }), s.eventsListeners && s.eventsListeners[e] && (i = [], s.eventsListeners[e].forEach(function (t) {
                        i.push(t)
                    }), i.forEach(function (t) {
                        t.apply(o, n)
                    }))
                }), s
            }
        }, update: {
            updateSize: function () {
                var t = this.$el,
                    e = void 0 !== this.params.width && null !== this.params.width ? this.params.width : t[0].clientWidth,
                    i = void 0 !== this.params.height && null !== this.params.width ? this.params.height : t[0].clientHeight;
                0 === e && this.isHorizontal() || 0 === i && this.isVertical() || (e = e - parseInt(t.css("padding-left") || 0, 10) - parseInt(t.css("padding-right") || 0, 10), i = i - parseInt(t.css("padding-top") || 0, 10) - parseInt(t.css("padding-bottom") || 0, 10), X(this, {
                    width: e = Number.isNaN(e) ? 0 : e,
                    height: i = Number.isNaN(i) ? 0 : i,
                    size: this.isHorizontal() ? e : i
                }))
            }, updateSlides: function () {
                var t = Y(), i = this.params, e = this.$wrapperEl, n = this.size, o = this.rtlTranslate,
                    s = this.wrongRTL, a = this.virtual && i.virtual.enabled,
                    r = (a ? this.virtual : this).slides.length, l = e.children("." + this.params.slideClass),
                    c = (a ? this.virtual.slides : l).length, d = [], u = [], h = [];

                function p(t, e) {
                    return !i.cssMode || e !== l.length - 1
                }

                var f = i.slidesOffsetBefore;
                "function" == typeof f && (f = i.slidesOffsetBefore.call(this));
                var m = i.slidesOffsetAfter;
                "function" == typeof m && (m = i.slidesOffsetAfter.call(this));
                var g, v = this.snapGrid.length, a = this.snapGrid.length, b = i.spaceBetween, y = -f, w = 0, x = 0;
                if (void 0 !== n) {
                    "string" == typeof b && 0 <= b.indexOf("%") && (b = parseFloat(b.replace("%", "")) / 100 * n), this.virtualSize = -b, o ? l.css({
                        marginLeft: "",
                        marginTop: ""
                    }) : l.css({
                        marginRight: "",
                        marginBottom: ""
                    }), 1 < i.slidesPerColumn && (g = Math.floor(c / i.slidesPerColumn) === c / this.params.slidesPerColumn ? c : Math.ceil(c / i.slidesPerColumn) * i.slidesPerColumn, "auto" !== i.slidesPerView && "row" === i.slidesPerColumnFill && (g = Math.max(g, i.slidesPerView * i.slidesPerColumn)));
                    for (var _, C, S, T, $ = i.slidesPerColumn, E = g / $, k = Math.floor(c / i.slidesPerColumn), I = 0; I < c; I += 1) {
                        L = 0;
                        var O, z, A, P, D, L, M, N, H, j, B, R, W = l.eq(I);
                        1 < i.slidesPerColumn && (D = P = A = void 0, "row" === i.slidesPerColumnFill && 1 < i.slidesPerGroup ? (R = Math.floor(I / (i.slidesPerGroup * i.slidesPerColumn)), O = I - i.slidesPerColumn * i.slidesPerGroup * R, z = 0 === R ? i.slidesPerGroup : Math.min(Math.ceil((c - R * $ * i.slidesPerGroup) / $), i.slidesPerGroup), A = (P = O - (D = Math.floor(O / z)) * z + R * i.slidesPerGroup) + D * g / $, W.css({
                            "-webkit-box-ordinal-group": A,
                            "-moz-box-ordinal-group": A,
                            "-ms-flex-order": A,
                            "-webkit-order": A,
                            order: A
                        })) : "column" === i.slidesPerColumnFill ? (D = I - (P = Math.floor(I / $)) * $, (k < P || P === k && D === $ - 1) && (D += 1) >= $ && (D = 0, P += 1)) : P = I - (D = Math.floor(I / E)) * E, W.css("margin-" + (this.isHorizontal() ? "top" : "left"), 0 !== D && i.spaceBetween && i.spaceBetween + "px")), "none" !== W.css("display") && ("auto" === i.slidesPerView ? (R = t.getComputedStyle(W[0], null), A = W[0].style.transform, P = W[0].style.webkitTransform, A && (W[0].style.transform = "none"), P && (W[0].style.webkitTransform = "none"), L = i.roundLengths ? this.isHorizontal() ? W.outerWidth(!0) : W.outerHeight(!0) : this.isHorizontal() ? (M = parseFloat(R.getPropertyValue("width") || 0), N = parseFloat(R.getPropertyValue("padding-left") || 0), H = parseFloat(R.getPropertyValue("padding-right") || 0), j = parseFloat(R.getPropertyValue("margin-left") || 0), B = parseFloat(R.getPropertyValue("margin-right") || 0), (D = R.getPropertyValue("box-sizing")) && "border-box" === D ? M + j + B : M + N + H + j + B) : (M = parseFloat(R.getPropertyValue("height") || 0), N = parseFloat(R.getPropertyValue("padding-top") || 0), H = parseFloat(R.getPropertyValue("padding-bottom") || 0), j = parseFloat(R.getPropertyValue("margin-top") || 0), B = parseFloat(R.getPropertyValue("margin-bottom") || 0), (R = R.getPropertyValue("box-sizing")) && "border-box" === R ? M + j + B : M + N + H + j + B), A && (W[0].style.transform = A), P && (W[0].style.webkitTransform = P), i.roundLengths && (L = Math.floor(L))) : (L = (n - (i.slidesPerView - 1) * b) / i.slidesPerView, i.roundLengths && (L = Math.floor(L)), l[I] && (this.isHorizontal() ? l[I].style.width = L + "px" : l[I].style.height = L + "px")), l[I] && (l[I].swiperSlideSize = L), h.push(L), i.centeredSlides ? (y = y + L / 2 + w / 2 + b, 0 === w && 0 !== I && (y = y - n / 2 - b), 0 === I && (y = y - n / 2 - b), Math.abs(y) < .001 && (y = 0), i.roundLengths && (y = Math.floor(y)), x % i.slidesPerGroup == 0 && d.push(y), u.push(y)) : (i.roundLengths && (y = Math.floor(y)), (x - Math.min(this.params.slidesPerGroupSkip, x)) % this.params.slidesPerGroup == 0 && d.push(y), u.push(y), y = y + L + b), this.virtualSize += L + b, w = L, x += 1)
                    }
                    if (this.virtualSize = Math.max(this.virtualSize, n) + m, o && s && ("slide" === i.effect || "coverflow" === i.effect) && e.css({width: this.virtualSize + i.spaceBetween + "px"}), i.setWrapperSize && (this.isHorizontal() ? e.css({width: this.virtualSize + i.spaceBetween + "px"}) : e.css({height: this.virtualSize + i.spaceBetween + "px"})), 1 < i.slidesPerColumn && (this.virtualSize = (L + i.spaceBetween) * g, this.virtualSize = Math.ceil(this.virtualSize / i.slidesPerColumn) - i.spaceBetween, this.isHorizontal() ? e.css({width: this.virtualSize + i.spaceBetween + "px"}) : e.css({height: this.virtualSize + i.spaceBetween + "px"}), i.centeredSlides)) {
                        for (var F = [], q = 0; q < d.length; q += 1) {
                            var V = d[q];
                            i.roundLengths && (V = Math.floor(V)), d[q] < this.virtualSize + d[0] && F.push(V)
                        }
                        d = F
                    }
                    if (!i.centeredSlides) {
                        F = [];
                        for (var U = 0; U < d.length; U += 1) {
                            var G = d[U];
                            i.roundLengths && (G = Math.floor(G)), d[U] <= this.virtualSize - n && F.push(G)
                        }
                        d = F, 1 < Math.floor(this.virtualSize - n) - Math.floor(d[d.length - 1]) && d.push(this.virtualSize - n)
                    }
                    0 === d.length && (d = [0]), 0 !== i.spaceBetween && (this.isHorizontal() ? o ? l.filter(p).css({marginLeft: b + "px"}) : l.filter(p).css({marginRight: b + "px"}) : l.filter(p).css({marginBottom: b + "px"})), i.centeredSlides && i.centeredSlidesBounds && (_ = 0, h.forEach(function (t) {
                        _ += t + (i.spaceBetween || 0)
                    }), C = (_ -= i.spaceBetween) - n, d = d.map(function (t) {
                        return t < 0 ? -f : C < t ? C + m : t
                    })), i.centerInsufficientSlides && (S = 0, h.forEach(function (t) {
                        S += t + (i.spaceBetween || 0)
                    }), (S -= i.spaceBetween) < n && (T = (n - S) / 2, d.forEach(function (t, e) {
                        d[e] = t - T
                    }), u.forEach(function (t, e) {
                        u[e] = t + T
                    }))), X(this, {
                        slides: l,
                        snapGrid: d,
                        slidesGrid: u,
                        slidesSizesGrid: h
                    }), c !== r && this.emit("slidesLengthChange"), d.length !== v && (this.params.watchOverflow && this.checkOverflow(), this.emit("snapGridLengthChange")), u.length !== a && this.emit("slidesGridLengthChange"), (i.watchSlidesProgress || i.watchSlidesVisibility) && this.updateSlidesOffset()
                }
            }, updateAutoHeight: function (t) {
                var e, i, n = [], o = 0;
                if ("number" == typeof t ? this.setTransition(t) : !0 === t && this.setTransition(this.params.speed), "auto" !== this.params.slidesPerView && 1 < this.params.slidesPerView) if (this.params.centeredSlides) this.visibleSlides.each(function (t) {
                    n.push(t)
                }); else for (e = 0; e < Math.ceil(this.params.slidesPerView); e += 1) {
                    var s = this.activeIndex + e;
                    if (s > this.slides.length) break;
                    n.push(this.slides.eq(s)[0])
                } else n.push(this.slides.eq(this.activeIndex)[0]);
                for (e = 0; e < n.length; e += 1) void 0 !== n[e] && (o = o < (i = n[e].offsetHeight) ? i : o);
                o && this.$wrapperEl.css("height", o + "px")
            }, updateSlidesOffset: function () {
                for (var t = this.slides, e = 0; e < t.length; e += 1) t[e].swiperSlideOffset = this.isHorizontal() ? t[e].offsetLeft : t[e].offsetTop
            }, updateSlidesProgress: function (t) {
                void 0 === t && (t = this && this.translate || 0);
                var e = this.params, i = this.slides, n = this.rtlTranslate;
                if (0 !== i.length) {
                    void 0 === i[0].swiperSlideOffset && this.updateSlidesOffset();
                    var o = n ? t : -t;
                    i.removeClass(e.slideVisibleClass), this.visibleSlidesIndexes = [], this.visibleSlides = [];
                    for (var s = 0; s < i.length; s += 1) {
                        var a, r, l = i[s],
                            c = (o + (e.centeredSlides ? this.minTranslate() : 0) - l.swiperSlideOffset) / (l.swiperSlideSize + e.spaceBetween);
                        (e.watchSlidesVisibility || e.centeredSlides && e.autoHeight) && (r = (a = -(o - l.swiperSlideOffset)) + this.slidesSizesGrid[s], (0 <= a && a < this.size - 1 || 1 < r && r <= this.size || a <= 0 && r >= this.size) && (this.visibleSlides.push(l), this.visibleSlidesIndexes.push(s), i.eq(s).addClass(e.slideVisibleClass))), l.progress = n ? -c : c
                    }
                    this.visibleSlides = C(this.visibleSlides)
                }
            }, updateProgress: function (t) {
                void 0 === t && (a = this.rtlTranslate ? -1 : 1, t = this && this.translate && this.translate * a || 0);
                var e = this.params, i = this.maxTranslate() - this.minTranslate(), n = this.progress,
                    o = this.isBeginning, s = o, a = r = this.isEnd,
                    r = 0 == i ? o = !(n = 0) : (o = (n = (t - this.minTranslate()) / i) <= 0, 1 <= n);
                X(this, {
                    progress: n,
                    isBeginning: o,
                    isEnd: r
                }), (e.watchSlidesProgress || e.watchSlidesVisibility || e.centeredSlides && e.autoHeight) && this.updateSlidesProgress(t), o && !s && this.emit("reachBeginning toEdge"), r && !a && this.emit("reachEnd toEdge"), (s && !o || a && !r) && this.emit("fromEdge"), this.emit("progress", n)
            }, updateSlidesClasses: function () {
                var t = this.slides, e = this.params, i = this.$wrapperEl, n = this.activeIndex, o = this.realIndex,
                    s = this.virtual && e.virtual.enabled;
                t.removeClass(e.slideActiveClass + " " + e.slideNextClass + " " + e.slidePrevClass + " " + e.slideDuplicateActiveClass + " " + e.slideDuplicateNextClass + " " + e.slideDuplicatePrevClass), (n = s ? this.$wrapperEl.find("." + e.slideClass + '[data-swiper-slide-index="' + n + '"]') : t.eq(n)).addClass(e.slideActiveClass), e.loop && (n.hasClass(e.slideDuplicateClass) ? i.children("." + e.slideClass + ":not(." + e.slideDuplicateClass + ')[data-swiper-slide-index="' + o + '"]') : i.children("." + e.slideClass + "." + e.slideDuplicateClass + '[data-swiper-slide-index="' + o + '"]')).addClass(e.slideDuplicateActiveClass);
                o = n.nextAll("." + e.slideClass).eq(0).addClass(e.slideNextClass);
                e.loop && 0 === o.length && (o = t.eq(0)).addClass(e.slideNextClass);
                n = n.prevAll("." + e.slideClass).eq(0).addClass(e.slidePrevClass);
                e.loop && 0 === n.length && (n = t.eq(-1)).addClass(e.slidePrevClass), e.loop && ((o.hasClass(e.slideDuplicateClass) ? i.children("." + e.slideClass + ":not(." + e.slideDuplicateClass + ')[data-swiper-slide-index="' + o.attr("data-swiper-slide-index") + '"]') : i.children("." + e.slideClass + "." + e.slideDuplicateClass + '[data-swiper-slide-index="' + o.attr("data-swiper-slide-index") + '"]')).addClass(e.slideDuplicateNextClass), (n.hasClass(e.slideDuplicateClass) ? i.children("." + e.slideClass + ":not(." + e.slideDuplicateClass + ')[data-swiper-slide-index="' + n.attr("data-swiper-slide-index") + '"]') : i.children("." + e.slideClass + "." + e.slideDuplicateClass + '[data-swiper-slide-index="' + n.attr("data-swiper-slide-index") + '"]')).addClass(e.slideDuplicatePrevClass)), this.emitSlidesClasses()
            }, updateActiveIndex: function (t) {
                var e = this.rtlTranslate ? this.translate : -this.translate, i = this.slidesGrid, n = this.snapGrid,
                    o = this.params, s = this.activeIndex, a = this.realIndex, r = this.snapIndex, l = t;
                if (void 0 === l) {
                    for (var c = 0; c < i.length; c += 1) void 0 !== i[c + 1] ? e >= i[c] && e < i[c + 1] - (i[c + 1] - i[c]) / 2 ? l = c : e >= i[c] && e < i[c + 1] && (l = c + 1) : e >= i[c] && (l = c);
                    o.normalizeSlideIndex && (l < 0 || void 0 === l) && (l = 0)
                }
                (o = 0 <= n.indexOf(e) ? n.indexOf(e) : (t = Math.min(o.slidesPerGroupSkip, l)) + Math.floor((l - t) / o.slidesPerGroup)) >= n.length && (o = n.length - 1), l !== s ? (n = parseInt(this.slides.eq(l).attr("data-swiper-slide-index") || l, 10), X(this, {
                    snapIndex: o,
                    realIndex: n,
                    previousIndex: s,
                    activeIndex: l
                }), this.emit("activeIndexChange"), this.emit("snapIndexChange"), a !== n && this.emit("realIndexChange"), (this.initialized || this.params.runCallbacksOnInit) && this.emit("slideChange")) : o !== r && (this.snapIndex = o, this.emit("snapIndexChange"))
            }, updateClickedSlide: function (t) {
                var e = this.params, i = C(t.target).closest("." + e.slideClass)[0], n = !1;
                if (i) for (var o = 0; o < this.slides.length; o += 1) this.slides[o] === i && (n = !0);
                if (!i || !n) return this.clickedSlide = void 0, void (this.clickedIndex = void 0);
                this.clickedSlide = i, this.virtual && this.params.virtual.enabled ? this.clickedIndex = parseInt(C(i).attr("data-swiper-slide-index"), 10) : this.clickedIndex = C(i).index(), e.slideToClickedSlide && void 0 !== this.clickedIndex && this.clickedIndex !== this.activeIndex && this.slideToClickedSlide()
            }
        }, translate: {
            getTranslate: function (t) {
                void 0 === t && (t = this.isHorizontal() ? "x" : "y");
                var e = this.params, i = this.rtlTranslate, n = this.translate, o = this.$wrapperEl;
                if (e.virtualTranslate) return i ? -n : n;
                if (e.cssMode) return n;
                t = b(o[0], t);
                return (t = i ? -t : t) || 0
            }, setTranslate: function (t, e) {
                var i = this.rtlTranslate, n = this.params, o = this.$wrapperEl, s = this.wrapperEl, a = this.progress,
                    r = 0, l = 0;
                this.isHorizontal() ? r = i ? -t : t : l = t, n.roundLengths && (r = Math.floor(r), l = Math.floor(l)), n.cssMode ? s[this.isHorizontal() ? "scrollLeft" : "scrollTop"] = this.isHorizontal() ? -r : -l : n.virtualTranslate || o.transform("translate3d(" + r + "px, " + l + "px, 0px)"), this.previousTranslate = this.translate, this.translate = this.isHorizontal() ? r : l;
                l = this.maxTranslate() - this.minTranslate();
                (0 == l ? 0 : (t - this.minTranslate()) / l) !== a && this.updateProgress(t), this.emit("setTranslate", this.translate, e)
            }, minTranslate: function () {
                return -this.snapGrid[0]
            }, maxTranslate: function () {
                return -this.snapGrid[this.snapGrid.length - 1]
            }, translateTo: function (t, e, i, n, o) {
                void 0 === t && (t = 0), void 0 === e && (e = this.params.speed), void 0 === i && (i = !0), void 0 === n && (n = !0);
                var s = this, a = s.params, r = s.wrapperEl;
                if (s.animating && a.preventInteractionOnTransition) return !1;
                var l = s.minTranslate(), c = s.maxTranslate(), c = n && l < t ? l : n && t < c ? c : t;
                if (s.updateProgress(c), a.cssMode) {
                    t = s.isHorizontal();
                    return 0 !== e && r.scrollTo ? r.scrollTo(((a = {})[t ? "left" : "top"] = -c, a.behavior = "smooth", a)) : r[t ? "scrollLeft" : "scrollTop"] = -c, !0
                }
                return 0 === e ? (s.setTransition(0), s.setTranslate(c), i && (s.emit("beforeTransitionStart", e, o), s.emit("transitionEnd"))) : (s.setTransition(e), s.setTranslate(c), i && (s.emit("beforeTransitionStart", e, o), s.emit("transitionStart")), s.animating || (s.animating = !0, s.onTranslateToWrapperTransitionEnd || (s.onTranslateToWrapperTransitionEnd = function (t) {
                    s && !s.destroyed && t.target === this && (s.$wrapperEl[0].removeEventListener("transitionend", s.onTranslateToWrapperTransitionEnd), s.$wrapperEl[0].removeEventListener("webkitTransitionEnd", s.onTranslateToWrapperTransitionEnd), s.onTranslateToWrapperTransitionEnd = null, delete s.onTranslateToWrapperTransitionEnd, i && s.emit("transitionEnd"))
                }), s.$wrapperEl[0].addEventListener("transitionend", s.onTranslateToWrapperTransitionEnd), s.$wrapperEl[0].addEventListener("webkitTransitionEnd", s.onTranslateToWrapperTransitionEnd))), !0
            }
        }, transition: {
            setTransition: function (t, e) {
                this.params.cssMode || this.$wrapperEl.transition(t), this.emit("setTransition", t, e)
            }, transitionStart: function (t, e) {
                void 0 === t && (t = !0);
                var i = this.activeIndex, n = this.params, o = this.previousIndex;
                n.cssMode || (n.autoHeight && this.updateAutoHeight(), e = (e = e) || (o < i ? "next" : i < o ? "prev" : "reset"), this.emit("transitionStart"), t && i !== o && ("reset" !== e ? (this.emit("slideChangeTransitionStart"), "next" === e ? this.emit("slideNextTransitionStart") : this.emit("slidePrevTransitionStart")) : this.emit("slideResetTransitionStart")))
            }, transitionEnd: function (t, e) {
                void 0 === t && (t = !0);
                var i = this.activeIndex, n = this.previousIndex, o = this.params;
                this.animating = !1, o.cssMode || (this.setTransition(0), e = (e = e) || (n < i ? "next" : i < n ? "prev" : "reset"), this.emit("transitionEnd"), t && i !== n && ("reset" !== e ? (this.emit("slideChangeTransitionEnd"), "next" === e ? this.emit("slideNextTransitionEnd") : this.emit("slidePrevTransitionEnd")) : this.emit("slideResetTransitionEnd")))
            }
        }, slide: {
            slideTo: function (t, e, i, n) {
                void 0 === e && (e = this.params.speed), void 0 === i && (i = !0);
                var o = this, s = t = void 0 === t ? 0 : t;
                s < 0 && (s = 0);
                var a = o.params, r = o.snapGrid, l = o.slidesGrid, c = o.previousIndex, d = o.activeIndex,
                    u = o.rtlTranslate, h = o.wrapperEl;
                if (o.animating && a.preventInteractionOnTransition) return !1;
                t = Math.min(o.params.slidesPerGroupSkip, s), t += Math.floor((s - t) / o.params.slidesPerGroup);
                t >= r.length && (t = r.length - 1), (d || a.initialSlide || 0) === (c || 0) && i && o.emit("beforeSlideChangeStart");
                var p, f = -r[t];
                if (o.updateProgress(f), a.normalizeSlideIndex) for (var m = 0; m < l.length; m += 1) -Math.floor(100 * f) >= Math.floor(100 * l[m]) && (s = m);
                if (o.initialized && s !== d) {
                    if (!o.allowSlideNext && f < o.translate && f < o.minTranslate()) return !1;
                    if (!o.allowSlidePrev && f > o.translate && f > o.maxTranslate() && (d || 0) !== s) return !1
                }
                if (p = d < s ? "next" : s < d ? "prev" : "reset", u && -f === o.translate || !u && f === o.translate) return o.updateActiveIndex(s), a.autoHeight && o.updateAutoHeight(), o.updateSlidesClasses(), "slide" !== a.effect && o.setTranslate(f), "reset" != p && (o.transitionStart(i, p), o.transitionEnd(i, p)), !1;
                if (a.cssMode) {
                    d = o.isHorizontal(), a = -f;
                    return u && (a = h.scrollWidth - h.offsetWidth - a), 0 !== e && h.scrollTo ? h.scrollTo(((u = {})[d ? "left" : "top"] = a, u.behavior = "smooth", u)) : h[d ? "scrollLeft" : "scrollTop"] = a, !0
                }
                return 0 === e ? (o.setTransition(0), o.setTranslate(f), o.updateActiveIndex(s), o.updateSlidesClasses(), o.emit("beforeTransitionStart", e, n), o.transitionStart(i, p), o.transitionEnd(i, p)) : (o.setTransition(e), o.setTranslate(f), o.updateActiveIndex(s), o.updateSlidesClasses(), o.emit("beforeTransitionStart", e, n), o.transitionStart(i, p), o.animating || (o.animating = !0, o.onSlideToWrapperTransitionEnd || (o.onSlideToWrapperTransitionEnd = function (t) {
                    o && !o.destroyed && t.target === this && (o.$wrapperEl[0].removeEventListener("transitionend", o.onSlideToWrapperTransitionEnd), o.$wrapperEl[0].removeEventListener("webkitTransitionEnd", o.onSlideToWrapperTransitionEnd), o.onSlideToWrapperTransitionEnd = null, delete o.onSlideToWrapperTransitionEnd, o.transitionEnd(i, p))
                }), o.$wrapperEl[0].addEventListener("transitionend", o.onSlideToWrapperTransitionEnd), o.$wrapperEl[0].addEventListener("webkitTransitionEnd", o.onSlideToWrapperTransitionEnd))), !0
            }, slideToLoop: function (t, e, i, n) {
                void 0 === e && (e = this.params.speed);
                t = void 0 === t ? 0 : t;
                return this.params.loop && (t += this.loopedSlides), this.slideTo(t, e, i = void 0 === i ? !0 : i, n)
            }, slideNext: function (t, e, i) {
                void 0 === t && (t = this.params.speed), void 0 === e && (e = !0);
                var n = this.params, o = this.animating,
                    s = this.activeIndex < n.slidesPerGroupSkip ? 1 : n.slidesPerGroup;
                if (n.loop) {
                    if (o && n.loopPreventsSlide) return !1;
                    this.loopFix(), this._clientLeft = this.$wrapperEl[0].clientLeft
                }
                return this.slideTo(this.activeIndex + s, t, e, i)
            }, slidePrev: function (t, e, i) {
                void 0 === t && (t = this.params.speed), void 0 === e && (e = !0);
                var n = this.params, o = this.animating, s = this.snapGrid, a = this.slidesGrid, r = this.rtlTranslate;
                if (n.loop) {
                    if (o && n.loopPreventsSlide) return !1;
                    this.loopFix(), this._clientLeft = this.$wrapperEl[0].clientLeft
                }

                function l(t) {
                    return t < 0 ? -Math.floor(Math.abs(t)) : Math.floor(t)
                }

                var c, d = l(r ? this.translate : -this.translate), r = s.map(l),
                    u = (s[r.indexOf(d)], s[r.indexOf(d) - 1]);
                return void 0 === u && n.cssMode && s.forEach(function (t) {
                    !u && t <= d && (u = t)
                }), void 0 !== u && (c = a.indexOf(u)) < 0 && (c = this.activeIndex - 1), this.slideTo(c, t, e, i)
            }, slideReset: function (t, e, i) {
                return void 0 === t && (t = this.params.speed), this.slideTo(this.activeIndex, t, e = void 0 === e ? !0 : e, i)
            }, slideToClosest: function (t, e, i, n) {
                void 0 === t && (t = this.params.speed), void 0 === e && (e = !0), void 0 === n && (n = .5);
                var o = this.activeIndex, s = Math.min(this.params.slidesPerGroupSkip, o),
                    a = s + Math.floor((o - s) / this.params.slidesPerGroup),
                    r = this.rtlTranslate ? this.translate : -this.translate;
                return r >= this.snapGrid[a] ? r - (s = this.snapGrid[a]) > (this.snapGrid[a + 1] - s) * n && (o += this.params.slidesPerGroup) : r - (r = this.snapGrid[a - 1]) <= (this.snapGrid[a] - r) * n && (o -= this.params.slidesPerGroup), o = Math.max(o, 0), o = Math.min(o, this.slidesGrid.length - 1), this.slideTo(o, t, e, i)
            }, slideToClickedSlide: function () {
                var t, e = this, i = e.params, n = e.$wrapperEl,
                    o = "auto" === i.slidesPerView ? e.slidesPerViewDynamic() : i.slidesPerView, s = e.clickedIndex;
                i.loop ? e.animating || (t = parseInt(C(e.clickedSlide).attr("data-swiper-slide-index"), 10), i.centeredSlides ? s < e.loopedSlides - o / 2 || s > e.slides.length - e.loopedSlides + o / 2 ? (e.loopFix(), s = n.children("." + i.slideClass + '[data-swiper-slide-index="' + t + '"]:not(.' + i.slideDuplicateClass + ")").eq(0).index(), S(function () {
                    e.slideTo(s)
                })) : e.slideTo(s) : s > e.slides.length - o ? (e.loopFix(), s = n.children("." + i.slideClass + '[data-swiper-slide-index="' + t + '"]:not(.' + i.slideDuplicateClass + ")").eq(0).index(), S(function () {
                    e.slideTo(s)
                })) : e.slideTo(s)) : e.slideTo(s)
            }
        }, loop: {
            loopCreate: function () {
                var n = this, t = v(), e = n.params, i = n.$wrapperEl;
                i.children("." + e.slideClass + "." + e.slideDuplicateClass).remove();
                var o = i.children("." + e.slideClass);
                if (e.loopFillGroupWithBlank) {
                    var s = e.slidesPerGroup - o.length % e.slidesPerGroup;
                    if (s !== e.slidesPerGroup) {
                        for (var a = 0; a < s; a += 1) {
                            var r = C(t.createElement("div")).addClass(e.slideClass + " " + e.slideBlankClass);
                            i.append(r)
                        }
                        o = i.children("." + e.slideClass)
                    }
                }
                "auto" !== e.slidesPerView || e.loopedSlides || (e.loopedSlides = o.length), n.loopedSlides = Math.ceil(parseFloat(e.loopedSlides || e.slidesPerView, 10)), n.loopedSlides += e.loopAdditionalSlides, n.loopedSlides > o.length && (n.loopedSlides = o.length);
                var l = [], c = [];
                o.each(function (t, e) {
                    var i = C(t);
                    e < n.loopedSlides && c.push(t), e < o.length && e >= o.length - n.loopedSlides && l.push(t), i.attr("data-swiper-slide-index", e)
                });
                for (var d = 0; d < c.length; d += 1) i.append(C(c[d].cloneNode(!0)).addClass(e.slideDuplicateClass));
                for (var u = l.length - 1; 0 <= u; --u) i.prepend(C(l[u].cloneNode(!0)).addClass(e.slideDuplicateClass))
            }, loopFix: function () {
                this.emit("beforeLoopFix");
                var t, e = this.activeIndex, i = this.slides, n = this.loopedSlides, o = this.allowSlidePrev,
                    s = this.allowSlideNext, a = this.snapGrid, r = this.rtlTranslate;
                this.allowSlidePrev = !0, this.allowSlideNext = !0;
                a = -a[e] - this.getTranslate();
                e < n ? (t = i.length - 3 * n + e, this.slideTo(t += n, 0, !1, !0) && 0 != a && this.setTranslate((r ? -this.translate : this.translate) - a)) : e >= i.length - n && (t = -i.length + e + n, this.slideTo(t += n, 0, !1, !0) && 0 != a && this.setTranslate((r ? -this.translate : this.translate) - a)), this.allowSlidePrev = o, this.allowSlideNext = s, this.emit("loopFix")
            }, loopDestroy: function () {
                var t = this.$wrapperEl, e = this.params, i = this.slides;
                t.children("." + e.slideClass + "." + e.slideDuplicateClass + ",." + e.slideClass + "." + e.slideBlankClass).remove(), i.removeAttr("data-swiper-slide-index")
            }
        }, grabCursor: {
            setGrabCursor: function (t) {
                var e;
                this.support.touch || !this.params.simulateTouch || this.params.watchOverflow && this.isLocked || this.params.cssMode || ((e = this.el).style.cursor = "move", e.style.cursor = t ? "-webkit-grabbing" : "-webkit-grab", e.style.cursor = t ? "-moz-grabbin" : "-moz-grab", e.style.cursor = t ? "grabbing" : "grab")
            }, unsetGrabCursor: function () {
                this.support.touch || this.params.watchOverflow && this.isLocked || this.params.cssMode || (this.el.style.cursor = "")
            }
        }, manipulation: {
            appendSlide: function (t) {
                var e = this.$wrapperEl, i = this.params;
                if (i.loop && this.loopDestroy(), "object" == typeof t && "length" in t) for (var n = 0; n < t.length; n += 1) t[n] && e.append(t[n]); else e.append(t);
                i.loop && this.loopCreate(), i.observer && this.support.observer || this.update()
            }, prependSlide: function (t) {
                var e = this.params, i = this.$wrapperEl, n = this.activeIndex;
                e.loop && this.loopDestroy();
                var o = n + 1;
                if ("object" == typeof t && "length" in t) {
                    for (var s = 0; s < t.length; s += 1) t[s] && i.prepend(t[s]);
                    o = n + t.length
                } else i.prepend(t);
                e.loop && this.loopCreate(), e.observer && this.support.observer || this.update(), this.slideTo(o, 0, !1)
            }, addSlide: function (t, e) {
                var i = this.$wrapperEl, n = this.params, o = this.activeIndex;
                n.loop && (o -= this.loopedSlides, this.loopDestroy(), this.slides = i.children("." + n.slideClass));
                var s = this.slides.length;
                if (t <= 0) this.prependSlide(e); else if (s <= t) this.appendSlide(e); else {
                    for (var a = t < o ? o + 1 : o, r = [], l = s - 1; t <= l; --l) {
                        var c = this.slides.eq(l);
                        c.remove(), r.unshift(c)
                    }
                    if ("object" == typeof e && "length" in e) {
                        for (var d = 0; d < e.length; d += 1) e[d] && i.append(e[d]);
                        a = t < o ? o + e.length : o
                    } else i.append(e);
                    for (var u = 0; u < r.length; u += 1) i.append(r[u]);
                    n.loop && this.loopCreate(), n.observer && this.support.observer || this.update(), n.loop ? this.slideTo(a + this.loopedSlides, 0, !1) : this.slideTo(a, 0, !1)
                }
            }, removeSlide: function (t) {
                var e = this.params, i = this.$wrapperEl, n = this.activeIndex;
                e.loop && (n -= this.loopedSlides, this.loopDestroy(), this.slides = i.children("." + e.slideClass));
                var o, s = n;
                if ("object" == typeof t && "length" in t) {
                    for (var a = 0; a < t.length; a += 1) o = t[a], this.slides[o] && this.slides.eq(o).remove(), o < s && --s;
                    s = Math.max(s, 0)
                } else this.slides[o = t] && this.slides.eq(o).remove(), o < s && --s, s = Math.max(s, 0);
                e.loop && this.loopCreate(), e.observer && this.support.observer || this.update(), e.loop ? this.slideTo(s + this.loopedSlides, 0, !1) : this.slideTo(s, 0, !1)
            }, removeAllSlides: function () {
                for (var t = [], e = 0; e < this.slides.length; e += 1) t.push(e);
                this.removeSlide(t)
            }
        }, events: {
            attachEvents: function () {
                var t = v(), e = this.params, i = this.touchEvents, n = this.el, o = this.wrapperEl, s = this.device,
                    a = this.support;
                this.onTouchStart = function (t) {
                    var e, i, n, o, s, a = v(), r = Y(), l = this.touchEventsData, c = this.params, d = this.touches;
                    this.animating && c.preventInteractionOnTransition || (i = C((e = (e = t).originalEvent ? e.originalEvent : e).target), "wrapper" === c.touchEventsTarget && !i.closest(this.wrapperEl).length || (l.isTouchEvent = "touchstart" === e.type, !l.isTouchEvent && "which" in e && 3 === e.which || !l.isTouchEvent && "button" in e && 0 < e.button || l.isTouched && l.isMoved) || (c.noSwiping && i.closest(c.noSwipingSelector || "." + c.noSwipingClass)[0] ? this.allowClick = !0 : c.swipeHandler && !i.closest(c.swipeHandler)[0] || (d.currentX = ("touchstart" === e.type ? e.targetTouches[0] : e).pageX, d.currentY = ("touchstart" === e.type ? e.targetTouches[0] : e).pageY, n = d.currentX, s = d.currentY, o = c.edgeSwipeDetection || c.iOSEdgeSwipeDetection, t = c.edgeSwipeThreshold || c.iOSEdgeSwipeThreshold, o && (n <= t || n >= r.screen.width - t) || (X(l, {
                        isTouched: !0,
                        isMoved: !1,
                        allowTouchCallbacks: !0,
                        isScrolling: void 0,
                        startMoving: void 0
                    }), d.startX = n, d.startY = s, l.touchStartTime = T(), this.allowClick = !0, this.updateSize(), this.swipeDirection = void 0, 0 < c.threshold && (l.allowThresholdMove = !1), "touchstart" !== e.type && (s = !0, i.is(l.formElements) && (s = !1), a.activeElement && C(a.activeElement).is(l.formElements) && a.activeElement !== i[0] && a.activeElement.blur(), s = s && this.allowTouchMove && c.touchStartPreventDefault, (c.touchStartForcePreventDefault || s) && e.preventDefault()), this.emit("touchStart", e)))))
                }.bind(this), this.onTouchMove = function (t) {
                    var e = v(), i = this.touchEventsData, n = this.params, o = this.touches, s = this.rtlTranslate,
                        a = t;
                    if (a.originalEvent && (a = a.originalEvent), i.isTouched) {
                        if (!i.isTouchEvent || "touchmove" === a.type) {
                            var r = "touchmove" === a.type && a.targetTouches && (a.targetTouches[0] || a.changedTouches[0]),
                                t = ("touchmove" === a.type ? r : a).pageX, r = ("touchmove" === a.type ? r : a).pageY;
                            if (a.preventedByNestedSwiper) return o.startX = t, void (o.startY = r);
                            if (!this.allowTouchMove) return this.allowClick = !1, void (i.isTouched && (X(o, {
                                startX: t,
                                startY: r,
                                currentX: t,
                                currentY: r
                            }), i.touchStartTime = T()));
                            if (i.isTouchEvent && n.touchReleaseOnEdges && !n.loop) if (this.isVertical()) {
                                if (r < o.startY && this.translate <= this.maxTranslate() || r > o.startY && this.translate >= this.minTranslate()) return i.isTouched = !1, void (i.isMoved = !1)
                            } else if (t < o.startX && this.translate <= this.maxTranslate() || t > o.startX && this.translate >= this.minTranslate()) return;
                            if (i.isTouchEvent && e.activeElement && a.target === e.activeElement && C(a.target).is(i.formElements)) return i.isMoved = !0, void (this.allowClick = !1);
                            if (i.allowTouchCallbacks && this.emit("touchMove", a), !(a.targetTouches && 1 < a.targetTouches.length)) {
                                o.currentX = t, o.currentY = r;
                                e = o.currentX - o.startX, t = o.currentY - o.startY;
                                if (!(this.params.threshold && Math.sqrt(Math.pow(e, 2) + Math.pow(t, 2)) < this.params.threshold)) if (void 0 === i.isScrolling && (this.isHorizontal() && o.currentY === o.startY || this.isVertical() && o.currentX === o.startX ? i.isScrolling = !1 : 25 <= e * e + t * t && (r = 180 * Math.atan2(Math.abs(t), Math.abs(e)) / Math.PI, i.isScrolling = this.isHorizontal() ? r > n.touchAngle : 90 - r > n.touchAngle)), i.isScrolling && this.emit("touchMoveOpposite", a), void 0 === i.startMoving && (o.currentX === o.startX && o.currentY === o.startY || (i.startMoving = !0)), i.isScrolling) i.isTouched = !1; else if (i.startMoving) {
                                    this.allowClick = !1, !n.cssMode && a.cancelable && a.preventDefault(), n.touchMoveStopPropagation && !n.nested && a.stopPropagation(), i.isMoved || (n.loop && this.loopFix(), i.startTranslate = this.getTranslate(), this.setTransition(0), this.animating && this.$wrapperEl.trigger("webkitTransitionEnd transitionend"), i.allowMomentumBounce = !1, !n.grabCursor || !0 !== this.allowSlideNext && !0 !== this.allowSlidePrev || this.setGrabCursor(!0), this.emit("sliderFirstMove", a)), this.emit("sliderMove", a), i.isMoved = !0;
                                    e = this.isHorizontal() ? e : t;
                                    o.diff = e, e *= n.touchRatio, this.swipeDirection = 0 < (e = s ? -e : e) ? "prev" : "next", i.currentTranslate = e + i.startTranslate;
                                    t = !0, s = n.resistanceRatio;
                                    if (n.touchReleaseOnEdges && (s = 0), 0 < e && i.currentTranslate > this.minTranslate() ? (t = !1, n.resistance && (i.currentTranslate = this.minTranslate() - 1 + Math.pow(-this.minTranslate() + i.startTranslate + e, s))) : e < 0 && i.currentTranslate < this.maxTranslate() && (t = !1, n.resistance && (i.currentTranslate = this.maxTranslate() + 1 - Math.pow(this.maxTranslate() - i.startTranslate - e, s))), t && (a.preventedByNestedSwiper = !0), !this.allowSlideNext && "next" === this.swipeDirection && i.currentTranslate < i.startTranslate && (i.currentTranslate = i.startTranslate), !this.allowSlidePrev && "prev" === this.swipeDirection && i.currentTranslate > i.startTranslate && (i.currentTranslate = i.startTranslate), 0 < n.threshold) {
                                        if (!(Math.abs(e) > n.threshold || i.allowThresholdMove)) return void (i.currentTranslate = i.startTranslate);
                                        if (!i.allowThresholdMove) return i.allowThresholdMove = !0, o.startX = o.currentX, o.startY = o.currentY, i.currentTranslate = i.startTranslate, void (o.diff = this.isHorizontal() ? o.currentX - o.startX : o.currentY - o.startY)
                                    }
                                    n.followFinger && !n.cssMode && ((n.freeMode || n.watchSlidesProgress || n.watchSlidesVisibility) && (this.updateActiveIndex(), this.updateSlidesClasses()), n.freeMode && (0 === i.velocities.length && i.velocities.push({
                                        position: o[this.isHorizontal() ? "startX" : "startY"],
                                        time: i.touchStartTime
                                    }), i.velocities.push({
                                        position: o[this.isHorizontal() ? "currentX" : "currentY"],
                                        time: T()
                                    })), this.updateProgress(i.currentTranslate), this.setTranslate(i.currentTranslate))
                                }
                            }
                        }
                    } else i.startMoving && i.isScrolling && this.emit("touchMoveOpposite", a)
                }.bind(this), this.onTouchEnd = function (t) {
                    var e = this, i = e.touchEventsData, n = e.params, o = e.touches, s = e.rtlTranslate,
                        a = e.$wrapperEl, r = e.slidesGrid, l = e.snapGrid, c = t;
                    if (c.originalEvent && (c = c.originalEvent), i.allowTouchCallbacks && e.emit("touchEnd", c), i.allowTouchCallbacks = !1, !i.isTouched) return i.isMoved && n.grabCursor && e.setGrabCursor(!1), i.isMoved = !1, void (i.startMoving = !1);
                    n.grabCursor && i.isMoved && i.isTouched && (!0 === e.allowSlideNext || !0 === e.allowSlidePrev) && e.setGrabCursor(!1);
                    var d, u = T(), t = u - i.touchStartTime;
                    if (e.allowClick && (e.updateClickedSlide(c), e.emit("tap click", c), t < 300 && u - i.lastClickTime < 300 && e.emit("doubleTap doubleClick", c)), i.lastClickTime = T(), S(function () {
                        e.destroyed || (e.allowClick = !0)
                    }), !i.isTouched || !i.isMoved || !e.swipeDirection || 0 === o.diff || i.currentTranslate === i.startTranslate) return i.isTouched = !1, i.isMoved = !1, void (i.startMoving = !1);
                    if (i.isTouched = !1, i.isMoved = !1, i.startMoving = !1, d = n.followFinger ? s ? e.translate : -e.translate : -i.currentTranslate, !n.cssMode) if (n.freeMode) if (d < -e.minTranslate()) e.slideTo(e.activeIndex); else if (d > -e.maxTranslate()) e.slides.length < l.length ? e.slideTo(l.length - 1) : e.slideTo(e.slides.length - 1); else {
                        if (n.freeModeMomentum) {
                            1 < i.velocities.length ? (v = i.velocities.pop(), p = i.velocities.pop(), h = v.position - p.position, p = v.time - p.time, e.velocity = h / p, e.velocity /= 2, Math.abs(e.velocity) < n.freeModeMinimumVelocity && (e.velocity = 0), (150 < p || 300 < T() - v.time) && (e.velocity = 0)) : e.velocity = 0, e.velocity *= n.freeModeMomentumVelocityRatio, i.velocities.length = 0;
                            var h = 1e3 * n.freeModeMomentumRatio, p = e.velocity * h, f = e.translate + p;
                            s && (f = -f);
                            var m, g, v = !1, p = 20 * Math.abs(e.velocity) * n.freeModeMomentumBounceRatio;
                            if (f < e.maxTranslate()) n.freeModeMomentumBounce ? (f + e.maxTranslate() < -p && (f = e.maxTranslate() - p), m = e.maxTranslate(), i.allowMomentumBounce = v = !0) : f = e.maxTranslate(), n.loop && n.centeredSlides && (g = !0); else if (f > e.minTranslate()) n.freeModeMomentumBounce ? (f - e.minTranslate() > p && (f = e.minTranslate() + p), m = e.minTranslate(), i.allowMomentumBounce = v = !0) : f = e.minTranslate(), n.loop && n.centeredSlides && (g = !0); else if (n.freeModeSticky) {
                                for (var b, y = 0; y < l.length; y += 1) if (l[y] > -f) {
                                    b = y;
                                    break
                                }
                                f = -(Math.abs(l[b] - f) < Math.abs(l[b - 1] - f) || "next" === e.swipeDirection ? l[b] : l[b - 1])
                            }
                            if (g && e.once("transitionEnd", function () {
                                e.loopFix()
                            }), 0 !== e.velocity) h = s ? Math.abs((-f - e.translate) / e.velocity) : Math.abs((f - e.translate) / e.velocity), n.freeModeSticky && (h = (g = Math.abs((s ? -f : f) - e.translate)) < (s = e.slidesSizesGrid[e.activeIndex]) ? n.speed : g < 2 * s ? 1.5 * n.speed : 2.5 * n.speed); else if (n.freeModeSticky) return void e.slideToClosest();
                            n.freeModeMomentumBounce && v ? (e.updateProgress(m), e.setTransition(h), e.setTranslate(f), e.transitionStart(!0, e.swipeDirection), e.animating = !0, a.transitionEnd(function () {
                                e && !e.destroyed && i.allowMomentumBounce && (e.emit("momentumBounce"), e.setTransition(n.speed), setTimeout(function () {
                                    e.setTranslate(m), a.transitionEnd(function () {
                                        e && !e.destroyed && e.transitionEnd()
                                    })
                                }, 0))
                            })) : e.velocity ? (e.updateProgress(f), e.setTransition(h), e.setTranslate(f), e.transitionStart(!0, e.swipeDirection), e.animating || (e.animating = !0, a.transitionEnd(function () {
                                e && !e.destroyed && e.transitionEnd()
                            }))) : e.updateProgress(f), e.updateActiveIndex(), e.updateSlidesClasses()
                        } else if (n.freeModeSticky) return void e.slideToClosest();
                        (!n.freeModeMomentum || t >= n.longSwipesMs) && (e.updateProgress(), e.updateActiveIndex(), e.updateSlidesClasses())
                    } else {
                        for (var w = 0, x = e.slidesSizesGrid[0], _ = 0; _ < r.length; _ += _ < n.slidesPerGroupSkip ? 1 : n.slidesPerGroup) {
                            var C = _ < n.slidesPerGroupSkip - 1 ? 1 : n.slidesPerGroup;
                            void 0 !== r[_ + C] ? d >= r[_] && d < r[_ + C] && (x = r[(w = _) + C] - r[_]) : d >= r[_] && (w = _, x = r[r.length - 1] - r[r.length - 2])
                        }
                        v = (d - r[w]) / x, h = w < n.slidesPerGroupSkip - 1 ? 1 : n.slidesPerGroup;
                        t > n.longSwipesMs ? n.longSwipes ? ("next" === e.swipeDirection && (v >= n.longSwipesRatio ? e.slideTo(w + h) : e.slideTo(w)), "prev" === e.swipeDirection && (v > 1 - n.longSwipesRatio ? e.slideTo(w + h) : e.slideTo(w))) : e.slideTo(e.activeIndex) : n.shortSwipes ? !e.navigation || c.target !== e.navigation.nextEl && c.target !== e.navigation.prevEl ? ("next" === e.swipeDirection && e.slideTo(w + h), "prev" === e.swipeDirection && e.slideTo(w)) : c.target === e.navigation.nextEl ? e.slideTo(w + h) : e.slideTo(w) : e.slideTo(e.activeIndex)
                    }
                }.bind(this), e.cssMode && (this.onScroll = function () {
                    var t = this.wrapperEl, e = this.rtlTranslate;
                    this.previousTranslate = this.translate, this.isHorizontal() ? this.translate = e ? t.scrollWidth - t.offsetWidth - t.scrollLeft : -t.scrollLeft : this.translate = -t.scrollTop, -0 === this.translate && (this.translate = 0), this.updateActiveIndex(), this.updateSlidesClasses(), (0 == (t = this.maxTranslate() - this.minTranslate()) ? 0 : (this.translate - this.minTranslate()) / t) !== this.progress && this.updateProgress(e ? -this.translate : this.translate), this.emit("setTranslate", this.translate, !1)
                }.bind(this)), this.onClick = function (t) {
                    this.allowClick || (this.params.preventClicks && t.preventDefault(), this.params.preventClicksPropagation && this.animating && (t.stopPropagation(), t.stopImmediatePropagation()))
                }.bind(this);
                var r, l = !!e.nested;
                !a.touch && a.pointerEvents ? (n.addEventListener(i.start, this.onTouchStart, !1), t.addEventListener(i.move, this.onTouchMove, l), t.addEventListener(i.end, this.onTouchEnd, !1)) : (a.touch && (r = !("touchstart" !== i.start || !a.passiveListener || !e.passiveListeners) && {
                    passive: !0,
                    capture: !1
                }, n.addEventListener(i.start, this.onTouchStart, r), n.addEventListener(i.move, this.onTouchMove, a.passiveListener ? {
                    passive: !1,
                    capture: l
                } : l), n.addEventListener(i.end, this.onTouchEnd, r), i.cancel && n.addEventListener(i.cancel, this.onTouchEnd, r), z || (t.addEventListener("touchstart", A), z = !0)), (e.simulateTouch && !s.ios && !s.android || e.simulateTouch && !a.touch && s.ios) && (n.addEventListener("mousedown", this.onTouchStart, !1), t.addEventListener("mousemove", this.onTouchMove, l), t.addEventListener("mouseup", this.onTouchEnd, !1))), (e.preventClicks || e.preventClicksPropagation) && n.addEventListener("click", this.onClick, !0), e.cssMode && o.addEventListener("scroll", this.onScroll), e.updateOnWindowResize ? this.on(s.ios || s.android ? "resize orientationchange observerUpdate" : "resize observerUpdate", O, !0) : this.on("observerUpdate", O, !0)
            }, detachEvents: function () {
                var t, e = v(), i = this.params, n = this.touchEvents, o = this.el, s = this.wrapperEl, a = this.device,
                    r = this.support, l = !!i.nested;
                !r.touch && r.pointerEvents ? (o.removeEventListener(n.start, this.onTouchStart, !1), e.removeEventListener(n.move, this.onTouchMove, l), e.removeEventListener(n.end, this.onTouchEnd, !1)) : (r.touch && (t = !("onTouchStart" !== n.start || !r.passiveListener || !i.passiveListeners) && {
                    passive: !0,
                    capture: !1
                }, o.removeEventListener(n.start, this.onTouchStart, t), o.removeEventListener(n.move, this.onTouchMove, l), o.removeEventListener(n.end, this.onTouchEnd, t), n.cancel && o.removeEventListener(n.cancel, this.onTouchEnd, t)), (i.simulateTouch && !a.ios && !a.android || i.simulateTouch && !r.touch && a.ios) && (o.removeEventListener("mousedown", this.onTouchStart, !1), e.removeEventListener("mousemove", this.onTouchMove, l), e.removeEventListener("mouseup", this.onTouchEnd, !1))), (i.preventClicks || i.preventClicksPropagation) && o.removeEventListener("click", this.onClick, !0), i.cssMode && s.removeEventListener("scroll", this.onScroll), this.off(a.ios || a.android ? "resize orientationchange observerUpdate" : "resize observerUpdate", O)
            }
        }, breakpoints: {
            setBreakpoint: function () {
                var t, i, e, n = this.activeIndex, o = this.initialized, s = this.loopedSlides,
                    a = void 0 === s ? 0 : s, r = this.params, l = this.$el, c = r.breakpoints;
                c && 0 !== Object.keys(c).length && (t = this.getBreakpoint(c)) && this.currentBreakpoint !== t && ((i = t in c ? c[t] : void 0) && ["slidesPerView", "spaceBetween", "slidesPerGroup", "slidesPerGroupSkip", "slidesPerColumn"].forEach(function (t) {
                    var e = i[t];
                    void 0 !== e && (i[t] = "slidesPerView" !== t || "AUTO" !== e && "auto" !== e ? "slidesPerView" === t ? parseFloat(e) : parseInt(e, 10) : "auto")
                }), e = i || this.originalParams, s = 1 < r.slidesPerColumn, c = 1 < e.slidesPerColumn, s && !c ? (l.removeClass(r.containerModifierClass + "multirow " + r.containerModifierClass + "multirow-column"), this.emitContainerClasses()) : !s && c && (l.addClass(r.containerModifierClass + "multirow"), "column" === e.slidesPerColumnFill && l.addClass(r.containerModifierClass + "multirow-column"), this.emitContainerClasses()), l = e.direction && e.direction !== r.direction, r = r.loop && (e.slidesPerView !== r.slidesPerView || l), l && o && this.changeDirection(), X(this.params, e), X(this, {
                    allowTouchMove: this.params.allowTouchMove,
                    allowSlideNext: this.params.allowSlideNext,
                    allowSlidePrev: this.params.allowSlidePrev
                }), this.currentBreakpoint = t, this.emit("_beforeBreakpoint", e), r && o && (this.loopDestroy(), this.loopCreate(), this.updateSlides(), this.slideTo(n - a + this.loopedSlides, 0, !1)), this.emit("breakpoint", e))
            }, getBreakpoint: function (t) {
                var i = Y();
                if (t) {
                    var e = !1, n = Object.keys(t).map(function (t) {
                        if ("string" != typeof t || 0 !== t.indexOf("@")) return {value: t, point: t};
                        var e = parseFloat(t.substr(1));
                        return {value: i.innerHeight * e, point: t}
                    });
                    n.sort(function (t, e) {
                        return parseInt(t.value, 10) - parseInt(e.value, 10)
                    });
                    for (var o = 0; o < n.length; o += 1) {
                        var s = n[o], a = s.point;
                        s.value <= i.innerWidth && (e = a)
                    }
                    return e || "max"
                }
            }
        }, checkOverflow: {
            checkOverflow: function () {
                var t = this.params, e = this.isLocked,
                    i = 0 < this.slides.length && t.slidesOffsetBefore + t.spaceBetween * (this.slides.length - 1) + this.slides[0].offsetWidth * this.slides.length;
                t.slidesOffsetBefore && t.slidesOffsetAfter && i ? this.isLocked = i <= this.size : this.isLocked = 1 === this.snapGrid.length, this.allowSlideNext = !this.isLocked, this.allowSlidePrev = !this.isLocked, e !== this.isLocked && this.emit(this.isLocked ? "lock" : "unlock"), e && e !== this.isLocked && (this.isEnd = !1, this.navigation && this.navigation.update())
            }
        }, classes: {
            addClasses: function () {
                var e = this.classNames, i = this.params, t = this.rtl, n = this.$el, o = this.device, s = [];
                s.push("initialized"), s.push(i.direction), i.freeMode && s.push("free-mode"), i.autoHeight && s.push("autoheight"), t && s.push("rtl"), 1 < i.slidesPerColumn && (s.push("multirow"), "column" === i.slidesPerColumnFill && s.push("multirow-column")), o.android && s.push("android"), o.ios && s.push("ios"), i.cssMode && s.push("css-mode"), s.forEach(function (t) {
                    e.push(i.containerModifierClass + t)
                }), n.addClass(e.join(" ")), this.emitContainerClasses()
            }, removeClasses: function () {
                var t = this.$el, e = this.classNames;
                t.removeClass(e.join(" ")), this.emitContainerClasses()
            }
        }, images: {
            loadImage: function (t, e, i, n, o, s) {
                var a = Y();

                function r() {
                    s && s()
                }

                !(C(t).parent("picture")[0] || t.complete && o) && e ? ((a = new a.Image).onload = r, a.onerror = r, n && (a.sizes = n), i && (a.srcset = i), e && (a.src = e)) : r()
            }, preloadImages: function () {
                var t = this;

                function e() {
                    null != t && t && !t.destroyed && (void 0 !== t.imagesLoaded && (t.imagesLoaded += 1), t.imagesLoaded === t.imagesToLoad.length && (t.params.updateOnImagesReady && t.update(), t.emit("imagesReady")))
                }

                t.imagesToLoad = t.$el.find("img");
                for (var i = 0; i < t.imagesToLoad.length; i += 1) {
                    var n = t.imagesToLoad[i];
                    t.loadImage(n, n.currentSrc || n.getAttribute("src"), n.srcset || n.getAttribute("srcset"), n.sizes || n.getAttribute("sizes"), !0, e)
                }
            }
        }
    }, N = {}, H = ((D = j.prototype).emitContainerClasses = function () {
        var t, e = this;
        e.params._emitClasses && e.el && (t = e.el.className.split(" ").filter(function (t) {
            return 0 === t.indexOf("swiper-container") || 0 === t.indexOf(e.params.containerModifierClass)
        }), e.emit("_containerClasses", t.join(" ")))
    }, D.emitSlidesClasses = function () {
        var i = this;
        i.params._emitClasses && i.el && i.slides.each(function (t) {
            var e = t.className.split(" ").filter(function (t) {
                return 0 === t.indexOf("swiper-slide") || 0 === t.indexOf(i.params.slideClass)
            });
            i.emit("_slideClass", t, e.join(" "))
        })
    }, D.slidesPerViewDynamic = function () {
        var t = this.params, e = this.slides, i = this.slidesGrid, n = this.size, o = this.activeIndex, s = 1;
        if (t.centeredSlides) {
            for (var a, r = e[o].swiperSlideSize, l = o + 1; l < e.length; l += 1) e[l] && !a && (s += 1, (r += e[l].swiperSlideSize) > n && (a = !0));
            for (var c = o - 1; 0 <= c; --c) e[c] && !a && (s += 1, (r += e[c].swiperSlideSize) > n && (a = !0))
        } else for (var d = o + 1; d < e.length; d += 1) i[d] - i[o] < n && (s += 1);
        return s
    }, D.update = function () {
        var t, e, i = this;

        function n() {
            var t = i.rtlTranslate ? -1 * i.translate : i.translate,
                t = Math.min(Math.max(t, i.maxTranslate()), i.minTranslate());
            i.setTranslate(t), i.updateActiveIndex(), i.updateSlidesClasses()
        }

        i && !i.destroyed && (t = i.snapGrid, (e = i.params).breakpoints && i.setBreakpoint(), i.updateSize(), i.updateSlides(), i.updateProgress(), i.updateSlidesClasses(), i.params.freeMode ? (n(), i.params.autoHeight && i.updateAutoHeight()) : (("auto" === i.params.slidesPerView || 1 < i.params.slidesPerView) && i.isEnd && !i.params.centeredSlides ? i.slideTo(i.slides.length - 1, 0, !1, !0) : i.slideTo(i.activeIndex, 0, !1, !0)) || n(), e.watchOverflow && t !== i.snapGrid && i.checkOverflow(), i.emit("update"))
    }, D.changeDirection = function (e, t) {
        void 0 === t && (t = !0);
        var i = this.params.direction;
        return (e = e || ("horizontal" === i ? "vertical" : "horizontal")) === i || "horizontal" !== e && "vertical" !== e || (this.$el.removeClass("" + this.params.containerModifierClass + i).addClass("" + this.params.containerModifierClass + e), this.emitContainerClasses(), this.params.direction = e, this.slides.each(function (t) {
            "vertical" === e ? t.style.width = "" : t.style.height = ""
        }), this.emit("changeDirection"), t && this.update()), this
    }, D.init = function () {
        this.initialized || (this.emit("beforeInit"), this.params.breakpoints && this.setBreakpoint(), this.addClasses(), this.params.loop && this.loopCreate(), this.updateSize(), this.updateSlides(), this.params.watchOverflow && this.checkOverflow(), this.params.grabCursor && this.setGrabCursor(), this.params.preloadImages && this.preloadImages(), this.params.loop ? this.slideTo(this.params.initialSlide + this.loopedSlides, 0, this.params.runCallbacksOnInit) : this.slideTo(this.params.initialSlide, 0, this.params.runCallbacksOnInit), this.attachEvents(), this.initialized = !0, this.emit("init"))
    }, D.destroy = function (t, e) {
        void 0 === t && (t = !0), void 0 === e && (e = !0);
        var i, n = this, o = n.params, s = n.$el, a = n.$wrapperEl, r = n.slides;
        return void 0 === n.params || n.destroyed || (n.emit("beforeDestroy"), n.initialized = !1, n.detachEvents(), o.loop && n.loopDestroy(), e && (n.removeClasses(), s.removeAttr("style"), a.removeAttr("style"), r && r.length && r.removeClass([o.slideVisibleClass, o.slideActiveClass, o.slideNextClass, o.slidePrevClass].join(" ")).removeAttr("style").removeAttr("data-swiper-slide-index")), n.emit("destroy"), Object.keys(n.eventsListeners).forEach(function (t) {
            n.off(t)
        }), !1 !== t && (n.$el[0].swiper = null, i = n, Object.keys(i).forEach(function (t) {
            try {
                i[t] = null
            } catch (t) {
            }
            try {
                delete i[t]
            } catch (t) {
            }
        })), n.destroyed = !0), null
    }, j.extendDefaults = function (t) {
        X(N, t)
    }, j.installModule = function (t) {
        j.prototype.modules || (j.prototype.modules = {});
        var e = t.name || Object.keys(j.prototype.modules).length + "_" + T();
        j.prototype.modules[e] = t
    }, j.use = function (t) {
        return Array.isArray(t) ? t.forEach(function (t) {
            return j.installModule(t)
        }) : j.installModule(t), j
    }, P = j, D = [{
        key: "extendedDefaults", get: function () {
            return N
        }
    }, {
        key: "defaults", get: function () {
            return L
        }
    }], null && t(P.prototype, null), t(P, D), j);

    function j() {
        for (var i, t = arguments.length, e = new Array(t), n = 0; n < t; n++) e[n] = arguments[n];
        i = X({}, i = (i = 1 === e.length && e[0].constructor && e[0].constructor === Object ? e[0] : (b = e[0], e[1])) || {}), b && !i.el && (i.el = b);
        var o, s, a, r, l, c, d, u, h, p, f, m = this;
        m.support = $(), m.device = (void 0 === (o = {userAgent: i.userAgent}) && (o = {}), x || (s = (void 0 === o ? {} : o).userAgent, a = $(), r = Y(), l = r.navigator.platform, c = s || r.navigator.userAgent, d = {
            ios: !1,
            android: !1
        }, u = r.screen.width, h = r.screen.height, p = c.match(/(Android);?[\s\/]+([\d.]+)?/), f = c.match(/(iPad).*OS\s([\d_]+)/), o = c.match(/(iPod)(.*OS\s([\d_]+))?/), s = !f && c.match(/(iPhone\sOS|iOS)\s([\d_]+)/), r = "Win32" === l, l = "MacIntel" === l, !f && l && a.touch && 0 <= ["1024x1366", "1366x1024", "834x1194", "1194x834", "834x1112", "1112x834", "768x1024", "1024x768"].indexOf(u + "x" + h) && ((f = c.match(/(Version)\/([\d.]+)/)) || (f = [0, 1, "13_0_0"]), l = !1), p && !r && (d.os = "android", d.android = !0), (f || s || o) && (d.os = "ios", d.ios = !0), x = d), x), m.browser = (_ || (g = Y(), _ = {
            isEdge: !!g.navigator.userAgent.match(/Edge/g),
            isSafari: 0 <= (d = g.navigator.userAgent.toLowerCase()).indexOf("safari") && d.indexOf("chrome") < 0 && d.indexOf("android") < 0,
            isWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(g.navigator.userAgent)
        }), _), m.eventsListeners = {}, m.eventsAnyListeners = [], void 0 === m.modules && (m.modules = {}), Object.keys(m.modules).forEach(function (t) {
            var e = m.modules[t];
            e.params && (t = Object.keys(e.params)[0], "object" == typeof (e = e.params[t]) && null !== e && t in i && "enabled" in e && (!0 === i[t] && (i[t] = {enabled: !0}), "object" != typeof i[t] || "enabled" in i[t] || (i[t].enabled = !0), i[t] || (i[t] = {enabled: !1})))
        });
        var g = X({}, L);
        m.useParams(g), m.params = X({}, g, N, i), m.originalParams = X({}, m.params), m.passedParams = X({}, i), m.params && m.params.on && Object.keys(m.params.on).forEach(function (t) {
            m.on(t, m.params.on[t])
        }), m.params && m.params.onAny && m.onAny(m.params.onAny);
        var v, b, y = (m.$ = C)(m.params.el);
        if (b = y[0]) {
            if (1 < y.length) {
                var w = [];
                return y.each(function (t) {
                    t = X({}, i, {el: t});
                    w.push(new j(t))
                }), w
            }
            return b.swiper = m, b && b.shadowRoot && b.shadowRoot.querySelector ? (v = C(b.shadowRoot.querySelector("." + m.params.wrapperClass))).children = function (t) {
                return y.children(t)
            } : v = y.children("." + m.params.wrapperClass), X(m, {
                $el: y,
                el: b,
                $wrapperEl: v,
                wrapperEl: v[0],
                classNames: [],
                slides: C(),
                slidesGrid: [],
                snapGrid: [],
                slidesSizesGrid: [],
                isHorizontal: function () {
                    return "horizontal" === m.params.direction
                },
                isVertical: function () {
                    return "vertical" === m.params.direction
                },
                rtl: "rtl" === b.dir.toLowerCase() || "rtl" === y.css("direction"),
                rtlTranslate: "horizontal" === m.params.direction && ("rtl" === b.dir.toLowerCase() || "rtl" === y.css("direction")),
                wrongRTL: "-webkit-box" === v.css("display"),
                activeIndex: 0,
                realIndex: 0,
                isBeginning: !0,
                isEnd: !1,
                translate: 0,
                previousTranslate: 0,
                progress: 0,
                velocity: 0,
                animating: !1,
                allowSlideNext: m.params.allowSlideNext,
                allowSlidePrev: m.params.allowSlidePrev,
                touchEvents: (b = ["mousedown", "mousemove", "mouseup"], m.support.pointerEvents && (b = ["pointerdown", "pointermove", "pointerup"]), m.touchEventsTouch = {
                    start: (v = ["touchstart", "touchmove", "touchend", "touchcancel"])[0],
                    move: v[1],
                    end: v[2],
                    cancel: v[3]
                }, m.touchEventsDesktop = {
                    start: b[0],
                    move: b[1],
                    end: b[2]
                }, m.support.touch || !m.params.simulateTouch ? m.touchEventsTouch : m.touchEventsDesktop),
                touchEventsData: {
                    isTouched: void 0,
                    isMoved: void 0,
                    allowTouchCallbacks: void 0,
                    touchStartTime: void 0,
                    isScrolling: void 0,
                    currentTranslate: void 0,
                    startTranslate: void 0,
                    allowThresholdMove: void 0,
                    formElements: "input, select, option, textarea, button, video, label",
                    lastClickTime: T(),
                    clickTimeout: void 0,
                    velocities: [],
                    allowMomentumBounce: void 0,
                    isTouchEvent: void 0,
                    startMoving: void 0
                },
                allowClick: !0,
                allowTouchMove: m.params.allowTouchMove,
                touches: {startX: 0, startY: 0, currentX: 0, currentY: 0, diff: 0},
                imagesToLoad: [],
                imagesLoaded: 0
            }), m.useModules(), m.emit("_swiper"), m.params.init && m.init(), m
        }
    }

    Object.keys(M).forEach(function (e) {
        Object.keys(M[e]).forEach(function (t) {
            H.prototype[t] = M[e][t]
        })
    }), H.use([E, I]);
    var B = {
        update: function (t) {
            var e = this, i = e.params, n = i.slidesPerView, o = i.slidesPerGroup, s = i.centeredSlides,
                a = e.params.virtual, r = a.addSlidesBefore, l = a.addSlidesAfter, c = e.virtual, d = c.from, u = c.to,
                h = c.slides, p = c.slidesGrid, f = c.renderSlide, i = c.offset;
            e.updateActiveIndex();
            var a = e.activeIndex || 0, c = e.rtlTranslate ? "right" : e.isHorizontal() ? "left" : "top",
                r = s ? (v = Math.floor(n / 2) + o + l, Math.floor(n / 2) + o + r) : (v = n + (o - 1) + l, o + r),
                m = Math.max((a || 0) - r, 0), g = Math.min((a || 0) + v, h.length - 1),
                v = (e.slidesGrid[m] || 0) - (e.slidesGrid[0] || 0);

            function b() {
                e.updateSlides(), e.updateProgress(), e.updateSlidesClasses(), e.lazy && e.params.lazy.enabled && e.lazy.load()
            }

            if (X(e.virtual, {
                from: m,
                to: g,
                offset: v,
                slidesGrid: e.slidesGrid
            }), d === m && u === g && !t) return e.slidesGrid !== p && v !== i && e.slides.css(c, v + "px"), void e.updateProgress();
            if (e.params.virtual.renderExternal) return e.params.virtual.renderExternal.call(e, {
                offset: v,
                from: m,
                to: g,
                slides: function () {
                    for (var t = [], e = m; e <= g; e += 1) t.push(h[e]);
                    return t
                }()
            }), void (e.params.virtual.renderExternalUpdate && b());
            var y = [], w = [];
            if (t) e.$wrapperEl.find("." + e.params.slideClass).remove(); else for (var x = d; x <= u; x += 1) (x < m || g < x) && e.$wrapperEl.find("." + e.params.slideClass + '[data-swiper-slide-index="' + x + '"]').remove();
            for (var _ = 0; _ < h.length; _ += 1) m <= _ && _ <= g && (void 0 === u || t ? w.push(_) : (u < _ && w.push(_), _ < d && y.push(_)));
            w.forEach(function (t) {
                e.$wrapperEl.append(f(h[t], t))
            }), y.sort(function (t, e) {
                return e - t
            }).forEach(function (t) {
                e.$wrapperEl.prepend(f(h[t], t))
            }), e.$wrapperEl.children(".swiper-slide").css(c, v + "px"), b()
        }, renderSlide: function (t, e) {
            var i = this.params.virtual;
            if (i.cache && this.virtual.cache[e]) return this.virtual.cache[e];
            t = i.renderSlide ? C(i.renderSlide.call(this, t, e)) : C('<div class="' + this.params.slideClass + '" data-swiper-slide-index="' + e + '">' + t + "</div>");
            return t.attr("data-swiper-slide-index") || t.attr("data-swiper-slide-index", e), i.cache && (this.virtual.cache[e] = t), t
        }, appendSlide: function (t) {
            if ("object" == typeof t && "length" in t) for (var e = 0; e < t.length; e += 1) t[e] && this.virtual.slides.push(t[e]); else this.virtual.slides.push(t);
            this.virtual.update(!0)
        }, prependSlide: function (t) {
            var n, o, e = this.activeIndex, i = e + 1, s = 1;
            if (Array.isArray(t)) {
                for (var a = 0; a < t.length; a += 1) t[a] && this.virtual.slides.unshift(t[a]);
                i = e + t.length, s = t.length
            } else this.virtual.slides.unshift(t);
            this.params.virtual.cache && (n = this.virtual.cache, o = {}, Object.keys(n).forEach(function (t) {
                var e = n[t], i = e.attr("data-swiper-slide-index");
                i && e.attr("data-swiper-slide-index", parseInt(i, 10) + 1), o[parseInt(t, 10) + s] = e
            }), this.virtual.cache = o), this.virtual.update(!0), this.slideTo(i, 0)
        }, removeSlide: function (t) {
            if (null != t) {
                var e = this.activeIndex;
                if (Array.isArray(t)) for (var i = t.length - 1; 0 <= i; --i) this.virtual.slides.splice(t[i], 1), this.params.virtual.cache && delete this.virtual.cache[t[i]], t[i] < e && --e, e = Math.max(e, 0); else this.virtual.slides.splice(t, 1), this.params.virtual.cache && delete this.virtual.cache[t], t < e && --e, e = Math.max(e, 0);
                this.virtual.update(!0), this.slideTo(e, 0)
            }
        }, removeAllSlides: function () {
            this.virtual.slides = [], this.params.virtual.cache && (this.virtual.cache = {}), this.virtual.update(!0), this.slideTo(0, 0)
        }
    }, E = {
        name: "virtual",
        params: {
            virtual: {
                enabled: !1,
                slides: [],
                cache: !0,
                renderSlide: null,
                renderExternal: null,
                renderExternalUpdate: !0,
                addSlidesBefore: 0,
                addSlidesAfter: 0
            }
        },
        create: function () {
            w(this, {virtual: e(e({}, B), {}, {slides: this.params.virtual.slides, cache: {}})})
        },
        on: {
            beforeInit: function (t) {
                var e;
                t.params.virtual.enabled && (t.classNames.push(t.params.containerModifierClass + "virtual"), X(t.params, e = {watchSlidesProgress: !0}), X(t.originalParams, e), t.params.initialSlide || t.virtual.update())
            }, setTranslate: function (t) {
                t.params.virtual.enabled && t.virtual.update()
            }
        }
    }, R = {
        handle: function (t) {
            var e = Y(), i = v(), n = this.rtlTranslate, o = t,
                s = (o = o.originalEvent ? o.originalEvent : o).keyCode || o.charCode,
                a = this.params.keyboard.pageUpDown, r = a && 33 === s, l = a && 34 === s, c = 37 === s, d = 39 === s,
                t = 38 === s, a = 40 === s;
            if (!this.allowSlideNext && (this.isHorizontal() && d || this.isVertical() && a || l)) return !1;
            if (!this.allowSlidePrev && (this.isHorizontal() && c || this.isVertical() && t || r)) return !1;
            if (!(o.shiftKey || o.altKey || o.ctrlKey || o.metaKey || i.activeElement && i.activeElement.nodeName && ("input" === i.activeElement.nodeName.toLowerCase() || "textarea" === i.activeElement.nodeName.toLowerCase()))) {
                if (this.params.keyboard.onlyInViewport && (r || l || c || d || t || a)) {
                    var u = !1;
                    if (0 < this.$el.parents("." + this.params.slideClass).length && 0 === this.$el.parents("." + this.params.slideActiveClass).length) return;
                    var h = e.innerWidth, p = e.innerHeight, e = this.$el.offset();
                    n && (e.left -= this.$el[0].scrollLeft);
                    for (var f = [[e.left, e.top], [e.left + this.width, e.top], [e.left, e.top + this.height], [e.left + this.width, e.top + this.height]], m = 0; m < f.length; m += 1) {
                        var g = f[m];
                        0 <= g[0] && g[0] <= h && 0 <= g[1] && g[1] <= p && (u = !0)
                    }
                    if (!u) return
                }
                this.isHorizontal() ? ((r || l || c || d) && (o.preventDefault ? o.preventDefault() : o.returnValue = !1), ((l || d) && !n || (r || c) && n) && this.slideNext(), ((r || c) && !n || (l || d) && n) && this.slidePrev()) : ((r || l || t || a) && (o.preventDefault ? o.preventDefault() : o.returnValue = !1), (l || a) && this.slideNext(), (r || t) && this.slidePrev()), this.emit("keyPress", s)
            }
        }, enable: function () {
            var t = v();
            this.keyboard.enabled || (C(t).on("keydown", this.keyboard.handle), this.keyboard.enabled = !0)
        }, disable: function () {
            var t = v();
            this.keyboard.enabled && (C(t).off("keydown", this.keyboard.handle), this.keyboard.enabled = !1)
        }
    }, I = {
        name: "keyboard",
        params: {keyboard: {enabled: !1, onlyInViewport: !0, pageUpDown: !0}},
        create: function () {
            w(this, {keyboard: e({enabled: !1}, R)})
        },
        on: {
            init: function (t) {
                t.params.keyboard.enabled && t.keyboard.enable()
            }, destroy: function (t) {
                t.keyboard.enabled && t.keyboard.disable()
            }
        }
    }, W = {
        lastScrollTime: T(), lastEventBeforeSnap: void 0, recentWheelEvents: [], event: function () {
            return -1 < Y().navigator.userAgent.indexOf("firefox") ? "DOMMouseScroll" : (e = v(), (i = "onwheel" in e) || ((t = e.createElement("div")).setAttribute("onwheel", "return;"), i = "function" == typeof t.onwheel), (i = !i && e.implementation && e.implementation.hasFeature && !0 !== e.implementation.hasFeature("", "") ? e.implementation.hasFeature("Events.wheel", "3.0") : i) ? "wheel" : "mousewheel");
            var t, e, i
        }, normalize: function (t) {
            var e = 0, i = 0, n = 0, o = 0;
            return "detail" in t && (i = t.detail), "wheelDelta" in t && (i = -t.wheelDelta / 120), "wheelDeltaY" in t && (i = -t.wheelDeltaY / 120), "wheelDeltaX" in t && (e = -t.wheelDeltaX / 120), "axis" in t && t.axis === t.HORIZONTAL_AXIS && (e = i, i = 0), n = 10 * e, o = 10 * i, "deltaY" in t && (o = t.deltaY), "deltaX" in t && (n = t.deltaX), t.shiftKey && !n && (n = o, o = 0), (n || o) && t.deltaMode && (1 === t.deltaMode ? (n *= 40, o *= 40) : (n *= 800, o *= 800)), {
                spinX: e = n && !e ? n < 1 ? -1 : 1 : e,
                spinY: i = o && !i ? o < 1 ? -1 : 1 : i,
                pixelX: n,
                pixelY: o
            }
        }, handleMouseEnter: function () {
            this.mouseEntered = !0
        }, handleMouseLeave: function () {
            this.mouseEntered = !1
        }, handle: function (t) {
            var e = t, i = this, n = i.params.mousewheel;
            i.params.cssMode && e.preventDefault();
            var o = i.$el;
            if ("container" !== i.params.mousewheel.eventsTarget && (o = C(i.params.mousewheel.eventsTarget)), !i.mouseEntered && !o[0].contains(e.target) && !n.releaseOnEdges) return !0;
            e.originalEvent && (e = e.originalEvent);
            var s = 0, a = i.rtlTranslate ? -1 : 1, o = W.normalize(e);
            if (n.forceToAxis) if (i.isHorizontal()) {
                if (!(Math.abs(o.pixelX) > Math.abs(o.pixelY))) return !0;
                s = -o.pixelX * a
            } else {
                if (!(Math.abs(o.pixelY) > Math.abs(o.pixelX))) return !0;
                s = -o.pixelY
            } else s = Math.abs(o.pixelX) > Math.abs(o.pixelY) ? -o.pixelX * a : -o.pixelY;
            if (0 === s) return !0;
            if (n.invert && (s = -s), i.params.freeMode) {
                var r = {time: T(), delta: Math.abs(s), direction: Math.sign(s)}, a = i.mousewheel.lastEventBeforeSnap,
                    o = a && r.time < a.time + 500 && r.delta <= a.delta && r.direction === a.direction;
                if (!o) {
                    i.mousewheel.lastEventBeforeSnap = void 0, i.params.loop && i.loopFix();
                    var l, c, d = i.getTranslate() + s * n.sensitivity, a = i.isBeginning, n = i.isEnd;
                    if ((d = d >= i.minTranslate() ? i.minTranslate() : d) <= i.maxTranslate() && (d = i.maxTranslate()), i.setTransition(0), i.setTranslate(d), i.updateProgress(), i.updateActiveIndex(), i.updateSlidesClasses(), (!a && i.isBeginning || !n && i.isEnd) && i.updateSlidesClasses(), i.params.freeModeSticky && (clearTimeout(i.mousewheel.timeout), i.mousewheel.timeout = void 0, 15 <= (l = i.mousewheel.recentWheelEvents).length && l.shift(), a = l.length ? l[l.length - 1] : void 0, n = l[0], l.push(r), a && (r.delta > a.delta || r.direction !== a.direction) ? l.splice(0) : 15 <= l.length && r.time - n.time < 500 && 1 <= n.delta - r.delta && r.delta <= 6 && (c = 0 < s ? .8 : .2, i.mousewheel.lastEventBeforeSnap = r, l.splice(0), i.mousewheel.timeout = S(function () {
                        i.slideToClosest(i.params.speed, !0, void 0, c)
                    }, 0)), i.mousewheel.timeout || (i.mousewheel.timeout = S(function () {
                        i.mousewheel.lastEventBeforeSnap = r, l.splice(0), i.slideToClosest(i.params.speed, !0, void 0, .5)
                    }, 500))), o || i.emit("scroll", e), i.params.autoplay && i.params.autoplayDisableOnInteraction && i.autoplay.stop(), d === i.minTranslate() || d === i.maxTranslate()) return !0
                }
            } else {
                d = {
                    time: T(),
                    delta: Math.abs(s),
                    direction: Math.sign(s),
                    raw: t
                }, s = i.mousewheel.recentWheelEvents;
                2 <= s.length && s.shift();
                t = s.length ? s[s.length - 1] : void 0;
                if (s.push(d), (!t || d.direction !== t.direction || d.delta > t.delta || d.time > t.time + 150) && i.mousewheel.animateSlider(d), i.mousewheel.releaseScroll(d)) return !0
            }
            return e.preventDefault ? e.preventDefault() : e.returnValue = !1, !1
        }, animateSlider: function (t) {
            var e = Y();
            return !(this.params.mousewheel.thresholdDelta && t.delta < this.params.mousewheel.thresholdDelta || this.params.mousewheel.thresholdTime && T() - this.mousewheel.lastScrollTime < this.params.mousewheel.thresholdTime || !(6 <= t.delta && T() - this.mousewheel.lastScrollTime < 60) && (t.direction < 0 ? this.isEnd && !this.params.loop || this.animating || (this.slideNext(), this.emit("scroll", t.raw)) : this.isBeginning && !this.params.loop || this.animating || (this.slidePrev(), this.emit("scroll", t.raw)), this.mousewheel.lastScrollTime = (new e.Date).getTime(), 1))
        }, releaseScroll: function (t) {
            var e = this.params.mousewheel;
            if (t.direction < 0) {
                if (this.isEnd && !this.params.loop && e.releaseOnEdges) return !0
            } else if (this.isBeginning && !this.params.loop && e.releaseOnEdges) return !0;
            return !1
        }, enable: function () {
            var t = W.event();
            if (this.params.cssMode) return this.wrapperEl.removeEventListener(t, this.mousewheel.handle), !0;
            if (!t) return !1;
            if (this.mousewheel.enabled) return !1;
            var e = this.$el;
            return (e = "container" !== this.params.mousewheel.eventsTarget ? C(this.params.mousewheel.eventsTarget) : e).on("mouseenter", this.mousewheel.handleMouseEnter), e.on("mouseleave", this.mousewheel.handleMouseLeave), e.on(t, this.mousewheel.handle), this.mousewheel.enabled = !0
        }, disable: function () {
            var t = W.event();
            if (this.params.cssMode) return this.wrapperEl.addEventListener(t, this.mousewheel.handle), !0;
            if (!t) return !1;
            if (!this.mousewheel.enabled) return !1;
            var e = this.$el;
            return (e = "container" !== this.params.mousewheel.eventsTarget ? C(this.params.mousewheel.eventsTarget) : e).off(t, this.mousewheel.handle), !(this.mousewheel.enabled = !1)
        }
    }, F = {
        update: function () {
            var t, e, i = this.params.navigation;
            this.params.loop || (t = (e = this.navigation).$nextEl, (e = e.$prevEl) && 0 < e.length && (this.isBeginning ? e.addClass(i.disabledClass) : e.removeClass(i.disabledClass), e[this.params.watchOverflow && this.isLocked ? "addClass" : "removeClass"](i.lockClass)), t && 0 < t.length && (this.isEnd ? t.addClass(i.disabledClass) : t.removeClass(i.disabledClass), t[this.params.watchOverflow && this.isLocked ? "addClass" : "removeClass"](i.lockClass)))
        }, onPrevClick: function (t) {
            t.preventDefault(), this.isBeginning && !this.params.loop || this.slidePrev()
        }, onNextClick: function (t) {
            t.preventDefault(), this.isEnd && !this.params.loop || this.slideNext()
        }, init: function () {
            var t, e, i = this.params.navigation;
            (i.nextEl || i.prevEl) && (i.nextEl && (t = C(i.nextEl), this.params.uniqueNavElements && "string" == typeof i.nextEl && 1 < t.length && 1 === this.$el.find(i.nextEl).length && (t = this.$el.find(i.nextEl))), i.prevEl && (e = C(i.prevEl), this.params.uniqueNavElements && "string" == typeof i.prevEl && 1 < e.length && 1 === this.$el.find(i.prevEl).length && (e = this.$el.find(i.prevEl))), t && 0 < t.length && t.on("click", this.navigation.onNextClick), e && 0 < e.length && e.on("click", this.navigation.onPrevClick), X(this.navigation, {
                $nextEl: t,
                nextEl: t && t[0],
                $prevEl: e,
                prevEl: e && e[0]
            }))
        }, destroy: function () {
            var t = this.navigation, e = t.$nextEl, t = t.$prevEl;
            e && e.length && (e.off("click", this.navigation.onNextClick), e.removeClass(this.params.navigation.disabledClass)), t && t.length && (t.off("click", this.navigation.onPrevClick), t.removeClass(this.params.navigation.disabledClass))
        }
    }, q = {
        update: function () {
            var t = this.rtl, i = this.params.pagination;
            if (i.el && this.pagination.el && this.pagination.$el && 0 !== this.pagination.$el.length) {
                var n, e = (this.virtual && this.params.virtual.enabled ? this.virtual : this).slides.length,
                    o = this.pagination.$el,
                    s = this.params.loop ? Math.ceil((e - 2 * this.loopedSlides) / this.params.slidesPerGroup) : this.snapGrid.length;
                if (this.params.loop ? ((n = Math.ceil((this.activeIndex - this.loopedSlides) / this.params.slidesPerGroup)) > e - 1 - 2 * this.loopedSlides && (n -= e - 2 * this.loopedSlides), s - 1 < n && (n -= s), n < 0 && "bullets" !== this.params.paginationType && (n = s + n)) : n = void 0 !== this.snapIndex ? this.snapIndex : this.activeIndex || 0, "bullets" === i.type && this.pagination.bullets && 0 < this.pagination.bullets.length) {
                    var a, r, l, c, d, u = this.pagination.bullets;
                    if (i.dynamicBullets && (this.pagination.bulletSize = u.eq(0)[this.isHorizontal() ? "outerWidth" : "outerHeight"](!0), o.css(this.isHorizontal() ? "width" : "height", this.pagination.bulletSize * (i.dynamicMainBullets + 4) + "px"), 1 < i.dynamicMainBullets && void 0 !== this.previousIndex && (this.pagination.dynamicBulletIndex += n - this.previousIndex, this.pagination.dynamicBulletIndex > i.dynamicMainBullets - 1 ? this.pagination.dynamicBulletIndex = i.dynamicMainBullets - 1 : this.pagination.dynamicBulletIndex < 0 && (this.pagination.dynamicBulletIndex = 0)), a = n - this.pagination.dynamicBulletIndex, l = ((r = a + (Math.min(u.length, i.dynamicMainBullets) - 1)) + a) / 2), u.removeClass(i.bulletActiveClass + " " + i.bulletActiveClass + "-next " + i.bulletActiveClass + "-next-next " + i.bulletActiveClass + "-prev " + i.bulletActiveClass + "-prev-prev " + i.bulletActiveClass + "-main"), 1 < o.length) u.each(function (t) {
                        var e = C(t), t = e.index();
                        t === n && e.addClass(i.bulletActiveClass), i.dynamicBullets && (a <= t && t <= r && e.addClass(i.bulletActiveClass + "-main"), t === a && e.prev().addClass(i.bulletActiveClass + "-prev").prev().addClass(i.bulletActiveClass + "-prev-prev"), t === r && e.next().addClass(i.bulletActiveClass + "-next").next().addClass(i.bulletActiveClass + "-next-next"))
                    }); else {
                        var h = u.eq(n), p = h.index();
                        if (h.addClass(i.bulletActiveClass), i.dynamicBullets) {
                            for (var e = u.eq(a), h = u.eq(r), f = a; f <= r; f += 1) u.eq(f).addClass(i.bulletActiveClass + "-main");
                            if (this.params.loop) if (p >= u.length - i.dynamicMainBullets) {
                                for (var m = i.dynamicMainBullets; 0 <= m; --m) u.eq(u.length - m).addClass(i.bulletActiveClass + "-main");
                                u.eq(u.length - i.dynamicMainBullets - 1).addClass(i.bulletActiveClass + "-prev")
                            } else e.prev().addClass(i.bulletActiveClass + "-prev").prev().addClass(i.bulletActiveClass + "-prev-prev"), h.next().addClass(i.bulletActiveClass + "-next").next().addClass(i.bulletActiveClass + "-next-next"); else e.prev().addClass(i.bulletActiveClass + "-prev").prev().addClass(i.bulletActiveClass + "-prev-prev"), h.next().addClass(i.bulletActiveClass + "-next").next().addClass(i.bulletActiveClass + "-next-next")
                        }
                    }
                    i.dynamicBullets && (d = Math.min(u.length, i.dynamicMainBullets + 4), c = (this.pagination.bulletSize * d - this.pagination.bulletSize) / 2 - l * this.pagination.bulletSize, d = t ? "right" : "left", u.css(this.isHorizontal() ? d : "top", c + "px"))
                }
                "fraction" === i.type && (o.find("." + i.currentClass).text(i.formatFractionCurrent(n + 1)), o.find("." + i.totalClass).text(i.formatFractionTotal(s))), "progressbar" === i.type && (l = i.progressbarOpposite ? this.isHorizontal() ? "vertical" : "horizontal" : this.isHorizontal() ? "horizontal" : "vertical", t = (n + 1) / s, c = d = 1, "horizontal" == l ? d = t : c = t, o.find("." + i.progressbarFillClass).transform("translate3d(0,0,0) scaleX(" + d + ") scaleY(" + c + ")").transition(this.params.speed)), "custom" === i.type && i.renderCustom ? (o.html(i.renderCustom(this, n + 1, s)), this.emit("paginationRender", o[0])) : this.emit("paginationUpdate", o[0]), o[this.params.watchOverflow && this.isLocked ? "addClass" : "removeClass"](i.lockClass)
            }
        }, render: function () {
            var t = this.params.pagination;
            if (t.el && this.pagination.el && this.pagination.$el && 0 !== this.pagination.$el.length) {
                var e = (this.virtual && this.params.virtual.enabled ? this.virtual : this).slides.length,
                    i = this.pagination.$el, n = "";
                if ("bullets" === t.type) {
                    for (var o = this.params.loop ? Math.ceil((e - 2 * this.loopedSlides) / this.params.slidesPerGroup) : this.snapGrid.length, s = 0; s < o; s += 1) t.renderBullet ? n += t.renderBullet.call(this, s, t.bulletClass) : n += "<" + t.bulletElement + ' class="' + t.bulletClass + '"></' + t.bulletElement + ">";
                    i.html(n), this.pagination.bullets = i.find("." + t.bulletClass)
                }
                "fraction" === t.type && (n = t.renderFraction ? t.renderFraction.call(this, t.currentClass, t.totalClass) : '<span class="' + t.currentClass + '"></span> / <span class="' + t.totalClass + '"></span>', i.html(n)), "progressbar" === t.type && (n = t.renderProgressbar ? t.renderProgressbar.call(this, t.progressbarFillClass) : '<span class="' + t.progressbarFillClass + '"></span>', i.html(n)), "custom" !== t.type && this.emit("paginationRender", this.pagination.$el[0])
            }
        }, init: function () {
            var t, e = this, i = e.params.pagination;
            !i.el || 0 !== (t = C(i.el)).length && (e.params.uniqueNavElements && "string" == typeof i.el && 1 < t.length && (t = e.$el.find(i.el)), "bullets" === i.type && i.clickable && t.addClass(i.clickableClass), t.addClass(i.modifierClass + i.type), "bullets" === i.type && i.dynamicBullets && (t.addClass("" + i.modifierClass + i.type + "-dynamic"), e.pagination.dynamicBulletIndex = 0, i.dynamicMainBullets < 1 && (i.dynamicMainBullets = 1)), "progressbar" === i.type && i.progressbarOpposite && t.addClass(i.progressbarOppositeClass), i.clickable && t.on("click", "." + i.bulletClass, function (t) {
                t.preventDefault();
                t = C(this).index() * e.params.slidesPerGroup;
                e.params.loop && (t += e.loopedSlides), e.slideTo(t)
            }), X(e.pagination, {$el: t, el: t[0]}))
        }, destroy: function () {
            var t, e = this.params.pagination;
            e.el && this.pagination.el && this.pagination.$el && 0 !== this.pagination.$el.length && ((t = this.pagination.$el).removeClass(e.hiddenClass), t.removeClass(e.modifierClass + e.type), this.pagination.bullets && this.pagination.bullets.removeClass(e.bulletActiveClass), e.clickable && t.off("click", "." + e.bulletClass))
        }
    }, V = {
        setTranslate: function () {
            var t, e, i, n, o, s, a, r;
            this.params.scrollbar.el && this.scrollbar.el && (a = this.scrollbar, t = this.rtlTranslate, r = this.progress, e = a.dragSize, i = a.trackSize, n = a.$dragEl, o = a.$el, s = this.params.scrollbar, r = (i - (a = e)) * r, t ? 0 < (r = -r) ? (a = e - r, r = 0) : i < -r + e && (a = i + r) : r < 0 ? (a = e + r, r = 0) : i < r + e && (a = i - r), this.isHorizontal() ? (n.transform("translate3d(" + r + "px, 0, 0)"), n[0].style.width = a + "px") : (n.transform("translate3d(0px, " + r + "px, 0)"), n[0].style.height = a + "px"), s.hide && (clearTimeout(this.scrollbar.timeout), o[0].style.opacity = 1, this.scrollbar.timeout = setTimeout(function () {
                o[0].style.opacity = 0, o.transition(400)
            }, 1e3)))
        }, setTransition: function (t) {
            this.params.scrollbar.el && this.scrollbar.el && this.scrollbar.$dragEl.transition(t)
        }, updateSize: function () {
            var t, e, i, n, o, s, a;
            this.params.scrollbar.el && this.scrollbar.el && (e = (t = this.scrollbar).$dragEl, i = t.$el, e[0].style.width = "", e[0].style.height = "", n = this.isHorizontal() ? i[0].offsetWidth : i[0].offsetHeight, s = (o = this.size / this.virtualSize) * (n / this.size), a = "auto" === this.params.scrollbar.dragSize ? n * o : parseInt(this.params.scrollbar.dragSize, 10), this.isHorizontal() ? e[0].style.width = a + "px" : e[0].style.height = a + "px", i[0].style.display = 1 <= o ? "none" : "", this.params.scrollbar.hide && (i[0].style.opacity = 0), X(t, {
                trackSize: n,
                divider: o,
                moveDivider: s,
                dragSize: a
            }), t.$el[this.params.watchOverflow && this.isLocked ? "addClass" : "removeClass"](this.params.scrollbar.lockClass))
        }, getPointerPosition: function (t) {
            return this.isHorizontal() ? ("touchstart" === t.type || "touchmove" === t.type ? t.targetTouches[0] : t).clientX : ("touchstart" === t.type || "touchmove" === t.type ? t.targetTouches[0] : t).clientY
        }, setDragPosition: function (t) {
            var e = this.scrollbar, i = this.rtlTranslate, n = e.$el, o = e.dragSize, s = e.trackSize,
                a = e.dragStartPos,
                o = (e.getPointerPosition(t) - n.offset()[this.isHorizontal() ? "left" : "top"] - (null !== a ? a : o / 2)) / (s - o);
            o = Math.max(Math.min(o, 1), 0), i && (o = 1 - o);
            o = this.minTranslate() + (this.maxTranslate() - this.minTranslate()) * o;
            this.updateProgress(o), this.setTranslate(o), this.updateActiveIndex(), this.updateSlidesClasses()
        }, onDragStart: function (t) {
            var e = this.params.scrollbar, i = this.scrollbar, n = this.$wrapperEl, o = i.$el, s = i.$dragEl;
            this.scrollbar.isTouched = !0, this.scrollbar.dragStartPos = t.target === s[0] || t.target === s ? i.getPointerPosition(t) - t.target.getBoundingClientRect()[this.isHorizontal() ? "left" : "top"] : null, t.preventDefault(), t.stopPropagation(), n.transition(100), s.transition(100), i.setDragPosition(t), clearTimeout(this.scrollbar.dragTimeout), o.transition(0), e.hide && o.css("opacity", 1), this.params.cssMode && this.$wrapperEl.css("scroll-snap-type", "none"), this.emit("scrollbarDragStart", t)
        }, onDragMove: function (t) {
            var e = this.scrollbar, i = this.$wrapperEl, n = e.$el, o = e.$dragEl;
            this.scrollbar.isTouched && (t.preventDefault ? t.preventDefault() : t.returnValue = !1, e.setDragPosition(t), i.transition(0), n.transition(0), o.transition(0), this.emit("scrollbarDragMove", t))
        }, onDragEnd: function (t) {
            var e = this.params.scrollbar, i = this.scrollbar, n = this.$wrapperEl, o = i.$el;
            this.scrollbar.isTouched && (this.scrollbar.isTouched = !1, this.params.cssMode && (this.$wrapperEl.css("scroll-snap-type", ""), n.transition("")), e.hide && (clearTimeout(this.scrollbar.dragTimeout), this.scrollbar.dragTimeout = S(function () {
                o.css("opacity", 0), o.transition(400)
            }, 1e3)), this.emit("scrollbarDragEnd", t), e.snapOnRelease && this.slideToClosest())
        }, enableDraggable: function () {
            var t, e, i, n, o, s, a;
            this.params.scrollbar.el && (t = v(), s = this.scrollbar, e = this.touchEventsTouch, i = this.touchEventsDesktop, a = this.params, n = this.support, o = s.$el[0], s = !(!n.passiveListener || !a.passiveListeners) && {
                passive: !1,
                capture: !1
            }, a = !(!n.passiveListener || !a.passiveListeners) && {
                passive: !0,
                capture: !1
            }, n.touch ? (o.addEventListener(e.start, this.scrollbar.onDragStart, s), o.addEventListener(e.move, this.scrollbar.onDragMove, s), o.addEventListener(e.end, this.scrollbar.onDragEnd, a)) : (o.addEventListener(i.start, this.scrollbar.onDragStart, s), t.addEventListener(i.move, this.scrollbar.onDragMove, s), t.addEventListener(i.end, this.scrollbar.onDragEnd, a)))
        }, disableDraggable: function () {
            var t, e, i, n, o, s, a;
            this.params.scrollbar.el && (t = v(), s = this.scrollbar, e = this.touchEventsTouch, i = this.touchEventsDesktop, a = this.params, n = this.support, o = s.$el[0], s = !(!n.passiveListener || !a.passiveListeners) && {
                passive: !1,
                capture: !1
            }, a = !(!n.passiveListener || !a.passiveListeners) && {
                passive: !0,
                capture: !1
            }, n.touch ? (o.removeEventListener(e.start, this.scrollbar.onDragStart, s), o.removeEventListener(e.move, this.scrollbar.onDragMove, s), o.removeEventListener(e.end, this.scrollbar.onDragEnd, a)) : (o.removeEventListener(i.start, this.scrollbar.onDragStart, s), t.removeEventListener(i.move, this.scrollbar.onDragMove, s), t.removeEventListener(i.end, this.scrollbar.onDragEnd, a)))
        }, init: function () {
            var t, e, i, n;
            this.params.scrollbar.el && (t = this.scrollbar, n = this.$el, i = C((e = this.params.scrollbar).el), 0 === (n = (i = this.params.uniqueNavElements && "string" == typeof e.el && 1 < i.length && 1 === n.find(e.el).length ? n.find(e.el) : i).find("." + this.params.scrollbar.dragClass)).length && (n = C('<div class="' + this.params.scrollbar.dragClass + '"></div>'), i.append(n)), X(t, {
                $el: i,
                el: i[0],
                $dragEl: n,
                dragEl: n[0]
            }), e.draggable && t.enableDraggable())
        }, destroy: function () {
            this.scrollbar.disableDraggable()
        }
    }, U = {
        setTransform: function (t, e) {
            var i = this.rtl, n = C(t), o = i ? -1 : 1, s = n.attr("data-swiper-parallax") || "0",
                a = n.attr("data-swiper-parallax-x"), r = n.attr("data-swiper-parallax-y"),
                t = n.attr("data-swiper-parallax-scale"), i = n.attr("data-swiper-parallax-opacity");
            a || r ? (a = a || "0", r = r || "0") : this.isHorizontal() ? (a = s, r = "0") : (r = s, a = "0"), a = 0 <= a.indexOf("%") ? parseInt(a, 10) * e * o + "%" : a * e * o + "px", r = 0 <= r.indexOf("%") ? parseInt(r, 10) * e + "%" : r * e + "px", null != i && (i = i - (i - 1) * (1 - Math.abs(e)), n[0].style.opacity = i), null == t ? n.transform("translate3d(" + a + ", " + r + ", 0px)") : (e = t - (t - 1) * (1 - Math.abs(e)), n.transform("translate3d(" + a + ", " + r + ", 0px) scale(" + e + ")"))
        }, setTranslate: function () {
            var n = this, t = n.$el, e = n.slides, o = n.progress, s = n.snapGrid;
            t.children("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]").each(function (t) {
                n.parallax.setTransform(t, o)
            }), e.each(function (t, e) {
                var i = t.progress;
                1 < n.params.slidesPerGroup && "auto" !== n.params.slidesPerView && (i += Math.ceil(e / 2) - o * (s.length - 1)), i = Math.min(Math.max(i, -1), 1), C(t).find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]").each(function (t) {
                    n.parallax.setTransform(t, i)
                })
            })
        }, setTransition: function (i) {
            void 0 === i && (i = this.params.speed), this.$el.find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y], [data-swiper-parallax-opacity], [data-swiper-parallax-scale]").each(function (t) {
                var e = C(t), t = parseInt(e.attr("data-swiper-parallax-duration"), 10) || i;
                0 === i && (t = 0), e.transition(t)
            })
        }
    }, G = {
        getDistanceBetweenTouches: function (t) {
            if (t.targetTouches.length < 2) return 1;
            var e = t.targetTouches[0].pageX, i = t.targetTouches[0].pageY, n = t.targetTouches[1].pageX,
                t = t.targetTouches[1].pageY;
            return Math.sqrt(Math.pow(n - e, 2) + Math.pow(t - i, 2))
        }, onGestureStart: function (t) {
            var e = this.support, i = this.params.zoom, n = this.zoom, o = n.gesture;
            if (n.fakeGestureTouched = !1, n.fakeGestureMoved = !1, !e.gestures) {
                if ("touchstart" !== t.type || "touchstart" === t.type && t.targetTouches.length < 2) return;
                n.fakeGestureTouched = !0, o.scaleStart = G.getDistanceBetweenTouches(t)
            }
            o.$slideEl && o.$slideEl.length || (o.$slideEl = C(t.target).closest("." + this.params.slideClass), 0 === o.$slideEl.length && (o.$slideEl = this.slides.eq(this.activeIndex)), o.$imageEl = o.$slideEl.find("img, svg, canvas, picture, .swiper-zoom-target"), o.$imageWrapEl = o.$imageEl.parent("." + i.containerClass), o.maxRatio = o.$imageWrapEl.attr("data-swiper-zoom") || i.maxRatio, 0 !== o.$imageWrapEl.length) ? (o.$imageEl && o.$imageEl.transition(0), this.zoom.isScaling = !0) : o.$imageEl = void 0
        }, onGestureChange: function (t) {
            var e = this.support, i = this.params.zoom, n = this.zoom, o = n.gesture;
            if (!e.gestures) {
                if ("touchmove" !== t.type || "touchmove" === t.type && t.targetTouches.length < 2) return;
                n.fakeGestureMoved = !0, o.scaleMove = G.getDistanceBetweenTouches(t)
            }
            o.$imageEl && 0 !== o.$imageEl.length ? (e.gestures ? n.scale = t.scale * n.currentScale : n.scale = o.scaleMove / o.scaleStart * n.currentScale, n.scale > o.maxRatio && (n.scale = o.maxRatio - 1 + Math.pow(n.scale - o.maxRatio + 1, .5)), n.scale < i.minRatio && (n.scale = i.minRatio + 1 - Math.pow(i.minRatio - n.scale + 1, .5)), o.$imageEl.transform("translate3d(0,0,0) scale(" + n.scale + ")")) : "gesturechange" === t.type && n.onGestureStart(t)
        }, onGestureEnd: function (t) {
            var e = this.device, i = this.support, n = this.params.zoom, o = this.zoom, s = o.gesture;
            if (!i.gestures) {
                if (!o.fakeGestureTouched || !o.fakeGestureMoved) return;
                if ("touchend" !== t.type || "touchend" === t.type && t.changedTouches.length < 2 && !e.android) return;
                o.fakeGestureTouched = !1, o.fakeGestureMoved = !1
            }
            s.$imageEl && 0 !== s.$imageEl.length && (o.scale = Math.max(Math.min(o.scale, s.maxRatio), n.minRatio), s.$imageEl.transition(this.params.speed).transform("translate3d(0,0,0) scale(" + o.scale + ")"), o.currentScale = o.scale, o.isScaling = !1, 1 === o.scale && (s.$slideEl = void 0))
        }, onTouchStart: function (t) {
            var e = this.device, i = this.zoom, n = i.gesture, i = i.image;
            n.$imageEl && 0 !== n.$imageEl.length && (i.isTouched || (e.android && t.cancelable && t.preventDefault(), i.isTouched = !0, i.touchesStart.x = ("touchstart" === t.type ? t.targetTouches[0] : t).pageX, i.touchesStart.y = ("touchstart" === t.type ? t.targetTouches[0] : t).pageY))
        }, onTouchMove: function (t) {
            var e = this.zoom, i = e.gesture, n = e.image, o = e.velocity;
            if (i.$imageEl && 0 !== i.$imageEl.length && (this.allowClick = !1, n.isTouched && i.$slideEl)) {
                n.isMoved || (n.width = i.$imageEl[0].offsetWidth, n.height = i.$imageEl[0].offsetHeight, n.startX = b(i.$imageWrapEl[0], "x") || 0, n.startY = b(i.$imageWrapEl[0], "y") || 0, i.slideWidth = i.$slideEl[0].offsetWidth, i.slideHeight = i.$slideEl[0].offsetHeight, i.$imageWrapEl.transition(0), this.rtl && (n.startX = -n.startX, n.startY = -n.startY));
                var s = n.width * e.scale, a = n.height * e.scale;
                if (!(s < i.slideWidth && a < i.slideHeight)) {
                    if (n.minX = Math.min(i.slideWidth / 2 - s / 2, 0), n.maxX = -n.minX, n.minY = Math.min(i.slideHeight / 2 - a / 2, 0), n.maxY = -n.minY, n.touchesCurrent.x = ("touchmove" === t.type ? t.targetTouches[0] : t).pageX, n.touchesCurrent.y = ("touchmove" === t.type ? t.targetTouches[0] : t).pageY, !n.isMoved && !e.isScaling) {
                        if (this.isHorizontal() && (Math.floor(n.minX) === Math.floor(n.startX) && n.touchesCurrent.x < n.touchesStart.x || Math.floor(n.maxX) === Math.floor(n.startX) && n.touchesCurrent.x > n.touchesStart.x)) return void (n.isTouched = !1);
                        if (!this.isHorizontal() && (Math.floor(n.minY) === Math.floor(n.startY) && n.touchesCurrent.y < n.touchesStart.y || Math.floor(n.maxY) === Math.floor(n.startY) && n.touchesCurrent.y > n.touchesStart.y)) return void (n.isTouched = !1)
                    }
                    t.cancelable && t.preventDefault(), t.stopPropagation(), n.isMoved = !0, n.currentX = n.touchesCurrent.x - n.touchesStart.x + n.startX, n.currentY = n.touchesCurrent.y - n.touchesStart.y + n.startY, n.currentX < n.minX && (n.currentX = n.minX + 1 - Math.pow(n.minX - n.currentX + 1, .8)), n.currentX > n.maxX && (n.currentX = n.maxX - 1 + Math.pow(n.currentX - n.maxX + 1, .8)), n.currentY < n.minY && (n.currentY = n.minY + 1 - Math.pow(n.minY - n.currentY + 1, .8)), n.currentY > n.maxY && (n.currentY = n.maxY - 1 + Math.pow(n.currentY - n.maxY + 1, .8)), o.prevPositionX || (o.prevPositionX = n.touchesCurrent.x), o.prevPositionY || (o.prevPositionY = n.touchesCurrent.y), o.prevTime || (o.prevTime = Date.now()), o.x = (n.touchesCurrent.x - o.prevPositionX) / (Date.now() - o.prevTime) / 2, o.y = (n.touchesCurrent.y - o.prevPositionY) / (Date.now() - o.prevTime) / 2, Math.abs(n.touchesCurrent.x - o.prevPositionX) < 2 && (o.x = 0), Math.abs(n.touchesCurrent.y - o.prevPositionY) < 2 && (o.y = 0), o.prevPositionX = n.touchesCurrent.x, o.prevPositionY = n.touchesCurrent.y, o.prevTime = Date.now(), i.$imageWrapEl.transform("translate3d(" + n.currentX + "px, " + n.currentY + "px,0)")
                }
            }
        }, onTouchEnd: function () {
            var t = this.zoom, e = t.gesture, i = t.image, n = t.velocity;
            if (e.$imageEl && 0 !== e.$imageEl.length) {
                if (!i.isTouched || !i.isMoved) return i.isTouched = !1, void (i.isMoved = !1);
                i.isTouched = !1, i.isMoved = !1;
                var o = 300, s = 300, a = n.x * o, r = i.currentX + a, a = n.y * s, a = i.currentY + a;
                0 !== n.x && (o = Math.abs((r - i.currentX) / n.x)), 0 !== n.y && (s = Math.abs((a - i.currentY) / n.y));
                s = Math.max(o, s);
                i.currentX = r, i.currentY = a;
                a = i.width * t.scale, t = i.height * t.scale;
                i.minX = Math.min(e.slideWidth / 2 - a / 2, 0), i.maxX = -i.minX, i.minY = Math.min(e.slideHeight / 2 - t / 2, 0), i.maxY = -i.minY, i.currentX = Math.max(Math.min(i.currentX, i.maxX), i.minX), i.currentY = Math.max(Math.min(i.currentY, i.maxY), i.minY), e.$imageWrapEl.transition(s).transform("translate3d(" + i.currentX + "px, " + i.currentY + "px,0)")
            }
        }, onTransitionEnd: function () {
            var t = this.zoom, e = t.gesture;
            e.$slideEl && this.previousIndex !== this.activeIndex && (e.$imageEl && e.$imageEl.transform("translate3d(0,0,0) scale(1)"), e.$imageWrapEl && e.$imageWrapEl.transform("translate3d(0,0,0)"), t.scale = 1, t.currentScale = 1, e.$slideEl = void 0, e.$imageEl = void 0, e.$imageWrapEl = void 0)
        }, toggle: function (t) {
            var e = this.zoom;
            e.scale && 1 !== e.scale ? e.out() : e.in(t)
        }, in: function (t) {
            var e, i, n, o = this.zoom, s = this.params.zoom, a = o.gesture, r = o.image;
            a.$slideEl || (this.params.virtual && this.params.virtual.enabled && this.virtual ? a.$slideEl = this.$wrapperEl.children("." + this.params.slideActiveClass) : a.$slideEl = this.slides.eq(this.activeIndex), a.$imageEl = a.$slideEl.find("img, svg, canvas, picture, .swiper-zoom-target"), a.$imageWrapEl = a.$imageEl.parent("." + s.containerClass)), a.$imageEl && 0 !== a.$imageEl.length && (a.$slideEl.addClass("" + s.zoomedSlideClass), r = void 0 === r.touchesStart.x && t ? (n = ("touchend" === t.type ? t.changedTouches[0] : t).pageX, ("touchend" === t.type ? t.changedTouches[0] : t).pageY) : (n = r.touchesStart.x, r.touchesStart.y), o.scale = a.$imageWrapEl.attr("data-swiper-zoom") || s.maxRatio, o.currentScale = a.$imageWrapEl.attr("data-swiper-zoom") || s.maxRatio, t ? (s = a.$slideEl[0].offsetWidth, t = a.$slideEl[0].offsetHeight, e = a.$slideEl.offset().left + s / 2 - n, i = a.$slideEl.offset().top + t / 2 - r, n = a.$imageEl[0].offsetWidth, r = a.$imageEl[0].offsetHeight, n = n * o.scale, r = r * o.scale, n = -(s = Math.min(s / 2 - n / 2, 0)), r = -(t = Math.min(t / 2 - r / 2, 0)), n < (e = (e = e * o.scale) < s ? s : e) && (e = n), r < (i = (i = i * o.scale) < t ? t : i) && (i = r)) : i = e = 0, a.$imageWrapEl.transition(300).transform("translate3d(" + e + "px, " + i + "px,0)"), a.$imageEl.transition(300).transform("translate3d(0,0,0) scale(" + o.scale + ")"))
        }, out: function () {
            var t = this.zoom, e = this.params.zoom, i = t.gesture;
            i.$slideEl || (this.params.virtual && this.params.virtual.enabled && this.virtual ? i.$slideEl = this.$wrapperEl.children("." + this.params.slideActiveClass) : i.$slideEl = this.slides.eq(this.activeIndex), i.$imageEl = i.$slideEl.find("img, svg, canvas, picture, .swiper-zoom-target"), i.$imageWrapEl = i.$imageEl.parent("." + e.containerClass)), i.$imageEl && 0 !== i.$imageEl.length && (t.scale = 1, t.currentScale = 1, i.$imageWrapEl.transition(300).transform("translate3d(0,0,0)"), i.$imageEl.transition(300).transform("translate3d(0,0,0) scale(1)"), i.$slideEl.removeClass("" + e.zoomedSlideClass), i.$slideEl = void 0)
        }, toggleGestures: function (t) {
            var e = this.zoom, i = e.slideSelector, n = e.passiveListener;
            this.$wrapperEl[t]("gesturestart", i, e.onGestureStart, n), this.$wrapperEl[t]("gesturechange", i, e.onGestureChange, n), this.$wrapperEl[t]("gestureend", i, e.onGestureEnd, n)
        }, enableGestures: function () {
            this.zoom.gesturesEnabled || (this.zoom.gesturesEnabled = !0, this.zoom.toggleGestures("on"))
        }, disableGestures: function () {
            this.zoom.gesturesEnabled && (this.zoom.gesturesEnabled = !1, this.zoom.toggleGestures("off"))
        }, enable: function () {
            var t, e, i, n = this.support, o = this.zoom;
            o.enabled || (o.enabled = !0, t = !("touchstart" !== this.touchEvents.start || !n.passiveListener || !this.params.passiveListeners) && {
                passive: !0,
                capture: !1
            }, e = !n.passiveListener || {
                passive: !1,
                capture: !0
            }, i = "." + this.params.slideClass, this.zoom.passiveListener = t, this.zoom.slideSelector = i, n.gestures ? (this.$wrapperEl.on(this.touchEvents.start, this.zoom.enableGestures, t), this.$wrapperEl.on(this.touchEvents.end, this.zoom.disableGestures, t)) : "touchstart" === this.touchEvents.start && (this.$wrapperEl.on(this.touchEvents.start, i, o.onGestureStart, t), this.$wrapperEl.on(this.touchEvents.move, i, o.onGestureChange, e), this.$wrapperEl.on(this.touchEvents.end, i, o.onGestureEnd, t), this.touchEvents.cancel && this.$wrapperEl.on(this.touchEvents.cancel, i, o.onGestureEnd, t)), this.$wrapperEl.on(this.touchEvents.move, "." + this.params.zoom.containerClass, o.onTouchMove, e))
        }, disable: function () {
            var t, e, i, n, o = this.zoom;
            o.enabled && (t = this.support, this.zoom.enabled = !1, e = !("touchstart" !== this.touchEvents.start || !t.passiveListener || !this.params.passiveListeners) && {
                passive: !0,
                capture: !1
            }, i = !t.passiveListener || {
                passive: !1,
                capture: !0
            }, n = "." + this.params.slideClass, t.gestures ? (this.$wrapperEl.off(this.touchEvents.start, this.zoom.enableGestures, e), this.$wrapperEl.off(this.touchEvents.end, this.zoom.disableGestures, e)) : "touchstart" === this.touchEvents.start && (this.$wrapperEl.off(this.touchEvents.start, n, o.onGestureStart, e), this.$wrapperEl.off(this.touchEvents.move, n, o.onGestureChange, i), this.$wrapperEl.off(this.touchEvents.end, n, o.onGestureEnd, e), this.touchEvents.cancel && this.$wrapperEl.off(this.touchEvents.cancel, n, o.onGestureEnd, e)), this.$wrapperEl.off(this.touchEvents.move, "." + this.params.zoom.containerClass, o.onTouchMove, i))
        }
    }, K = {
        loadInSlide: function (t, l) {
            void 0 === l && (l = !0);
            var c, d = this, u = d.params.lazy;
            void 0 !== t && 0 !== d.slides.length && (t = (c = d.virtual && d.params.virtual.enabled ? d.$wrapperEl.children("." + d.params.slideClass + '[data-swiper-slide-index="' + t + '"]') : d.slides.eq(t)).find("." + u.elementClass + ":not(." + u.loadedClass + "):not(." + u.loadingClass + ")"), !c.hasClass(u.elementClass) || c.hasClass(u.loadedClass) || c.hasClass(u.loadingClass) || t.push(c[0]), 0 !== t.length && t.each(function (t) {
                var i = C(t);
                i.addClass(u.loadingClass);
                var n = i.attr("data-background"), o = i.attr("data-src"), s = i.attr("data-srcset"),
                    a = i.attr("data-sizes"), r = i.parent("picture");
                d.loadImage(i[0], o || n, s, a, !1, function () {
                    var t, e;
                    null == d || !d || d && !d.params || d.destroyed || (n ? (i.css("background-image", 'url("' + n + '")'), i.removeAttr("data-background")) : (s && (i.attr("srcset", s), i.removeAttr("data-srcset")), a && (i.attr("sizes", a), i.removeAttr("data-sizes")), r.length && r.children("source").each(function (t) {
                        t = C(t);
                        t.attr("data-srcset") && (t.attr("srcset", t.attr("data-srcset")), t.removeAttr("data-srcset"))
                    }), o && (i.attr("src", o), i.removeAttr("data-src"))), i.addClass(u.loadedClass).removeClass(u.loadingClass), c.find("." + u.preloaderClass).remove(), d.params.loop && l && (e = c.attr("data-swiper-slide-index"), c.hasClass(d.params.slideDuplicateClass) ? (t = d.$wrapperEl.children('[data-swiper-slide-index="' + e + '"]:not(.' + d.params.slideDuplicateClass + ")"), d.lazy.loadInSlide(t.index(), !1)) : (e = d.$wrapperEl.children("." + d.params.slideDuplicateClass + '[data-swiper-slide-index="' + e + '"]'), d.lazy.loadInSlide(e.index(), !1))), d.emit("lazyImageReady", c[0], i[0]), d.params.autoHeight && d.updateAutoHeight())
                }), d.emit("lazyImageLoad", c[0], i[0])
            }))
        }, load: function () {
            var e = this, i = e.$wrapperEl, n = e.params, o = e.slides, t = e.activeIndex,
                s = e.virtual && n.virtual.enabled, a = n.lazy, r = n.slidesPerView;

            function l(t) {
                if (s) {
                    if (i.children("." + n.slideClass + '[data-swiper-slide-index="' + t + '"]').length) return 1
                } else if (o[t]) return 1
            }

            function c(t) {
                return s ? C(t).attr("data-swiper-slide-index") : C(t).index()
            }

            if ("auto" === r && (r = 0), e.lazy.initialImageLoaded || (e.lazy.initialImageLoaded = !0), e.params.watchSlidesVisibility) i.children("." + n.slideVisibleClass).each(function (t) {
                t = s ? C(t).attr("data-swiper-slide-index") : C(t).index();
                e.lazy.loadInSlide(t)
            }); else if (1 < r) for (var d = t; d < t + r; d += 1) l(d) && e.lazy.loadInSlide(d); else e.lazy.loadInSlide(t);
            if (a.loadPrevNext) if (1 < r || a.loadPrevNextAmount && 1 < a.loadPrevNextAmount) {
                for (var u = a.loadPrevNextAmount, a = r, h = Math.min(t + a + Math.max(u, a), o.length), u = Math.max(t - Math.max(a, u), 0), p = t + r; p < h; p += 1) l(p) && e.lazy.loadInSlide(p);
                for (var f = u; f < t; f += 1) l(f) && e.lazy.loadInSlide(f)
            } else {
                u = i.children("." + n.slideNextClass);
                0 < u.length && e.lazy.loadInSlide(c(u));
                u = i.children("." + n.slidePrevClass);
                0 < u.length && e.lazy.loadInSlide(c(u))
            }
        }
    }, Z = {
        LinearSpline: function (t, e) {
            var i, n, o, s, a;
            return this.x = t, this.y = e, this.lastIndex = t.length - 1, this.interpolate = function (t) {
                return t ? (a = function (t, e) {
                    for (n = -1, i = t.length; 1 < i - n;) t[o = i + n >> 1] <= e ? n = o : i = o;
                    return i
                }(this.x, t), s = a - 1, (t - this.x[s]) * (this.y[a] - this.y[s]) / (this.x[a] - this.x[s]) + this.y[s]) : 0
            }, this
        }, getInterpolateFunction: function (t) {
            this.controller.spline || (this.controller.spline = this.params.loop ? new Z.LinearSpline(this.slidesGrid, t.slidesGrid) : new Z.LinearSpline(this.snapGrid, t.snapGrid))
        }, setTranslate: function (t, e) {
            var i, n, o = this, s = o.controller.control, a = o.constructor;

            function r(t) {
                var e = o.rtlTranslate ? -o.translate : o.translate;
                "slide" === o.params.controller.by && (o.controller.getInterpolateFunction(t), n = -o.controller.spline.interpolate(-e)), n && "container" !== o.params.controller.by || (i = (t.maxTranslate() - t.minTranslate()) / (o.maxTranslate() - o.minTranslate()), n = (e - o.minTranslate()) * i + t.minTranslate()), o.params.controller.inverse && (n = t.maxTranslate() - n), t.updateProgress(n), t.setTranslate(n, o), t.updateActiveIndex(), t.updateSlidesClasses()
            }

            if (Array.isArray(s)) for (var l = 0; l < s.length; l += 1) s[l] !== e && s[l] instanceof a && r(s[l]); else s instanceof a && e !== s && r(s)
        }, setTransition: function (e, t) {
            var i, n = this, o = n.constructor, s = n.controller.control;

            function a(t) {
                t.setTransition(e, n), 0 !== e && (t.transitionStart(), t.params.autoHeight && S(function () {
                    t.updateAutoHeight()
                }), t.$wrapperEl.transitionEnd(function () {
                    s && (t.params.loop && "slide" === n.params.controller.by && t.loopFix(), t.transitionEnd())
                }))
            }

            if (Array.isArray(s)) for (i = 0; i < s.length; i += 1) s[i] !== t && s[i] instanceof o && a(s[i]); else s instanceof o && t !== s && a(s)
        }
    }, Q = {
        makeElFocusable: function (t) {
            return t.attr("tabIndex", "0"), t
        }, makeElNotFocusable: function (t) {
            return t.attr("tabIndex", "-1"), t
        }, addElRole: function (t, e) {
            return t.attr("role", e), t
        }, addElLabel: function (t, e) {
            return t.attr("aria-label", e), t
        }, disableEl: function (t) {
            return t.attr("aria-disabled", !0), t
        }, enableEl: function (t) {
            return t.attr("aria-disabled", !1), t
        }, onEnterKey: function (t) {
            var e = this.params.a11y;
            13 === t.keyCode && (t = C(t.target), this.navigation && this.navigation.$nextEl && t.is(this.navigation.$nextEl) && (this.isEnd && !this.params.loop || this.slideNext(), this.isEnd ? this.a11y.notify(e.lastSlideMessage) : this.a11y.notify(e.nextSlideMessage)), this.navigation && this.navigation.$prevEl && t.is(this.navigation.$prevEl) && (this.isBeginning && !this.params.loop || this.slidePrev(), this.isBeginning ? this.a11y.notify(e.firstSlideMessage) : this.a11y.notify(e.prevSlideMessage)), this.pagination && t.is("." + this.params.pagination.bulletClass) && t[0].click())
        }, notify: function (t) {
            var e = this.a11y.liveRegion;
            0 !== e.length && (e.html(""), e.html(t))
        }, updateNavigation: function () {
            var t, e;
            !this.params.loop && this.navigation && (t = (e = this.navigation).$nextEl, (e = e.$prevEl) && 0 < e.length && (this.isBeginning ? (this.a11y.disableEl(e), this.a11y.makeElNotFocusable(e)) : (this.a11y.enableEl(e), this.a11y.makeElFocusable(e))), t && 0 < t.length && (this.isEnd ? (this.a11y.disableEl(t), this.a11y.makeElNotFocusable(t)) : (this.a11y.enableEl(t), this.a11y.makeElFocusable(t))))
        }, updatePagination: function () {
            var e = this, i = e.params.a11y;
            e.pagination && e.params.pagination.clickable && e.pagination.bullets && e.pagination.bullets.length && e.pagination.bullets.each(function (t) {
                t = C(t);
                e.a11y.makeElFocusable(t), e.params.pagination.renderBullet || (e.a11y.addElRole(t, "button"), e.a11y.addElLabel(t, i.paginationBulletMessage.replace(/\{\{index\}\}/, t.index() + 1)))
            })
        }, init: function () {
            this.$el.append(this.a11y.liveRegion);
            var t, e, i = this.params.a11y;
            this.navigation && this.navigation.$nextEl && (t = this.navigation.$nextEl), this.navigation && this.navigation.$prevEl && (e = this.navigation.$prevEl), t && (this.a11y.makeElFocusable(t), this.a11y.addElRole(t, "button"), this.a11y.addElLabel(t, i.nextSlideMessage), t.on("keydown", this.a11y.onEnterKey)), e && (this.a11y.makeElFocusable(e), this.a11y.addElRole(e, "button"), this.a11y.addElLabel(e, i.prevSlideMessage), e.on("keydown", this.a11y.onEnterKey)), this.pagination && this.params.pagination.clickable && this.pagination.bullets && this.pagination.bullets.length && this.pagination.$el.on("keydown", "." + this.params.pagination.bulletClass, this.a11y.onEnterKey)
        }, destroy: function () {
            var t, e;
            this.a11y.liveRegion && 0 < this.a11y.liveRegion.length && this.a11y.liveRegion.remove(), this.navigation && this.navigation.$nextEl && (t = this.navigation.$nextEl), this.navigation && this.navigation.$prevEl && (e = this.navigation.$prevEl), t && t.off("keydown", this.a11y.onEnterKey), e && e.off("keydown", this.a11y.onEnterKey), this.pagination && this.params.pagination.clickable && this.pagination.bullets && this.pagination.bullets.length && this.pagination.$el.off("keydown", "." + this.params.pagination.bulletClass, this.a11y.onEnterKey)
        }
    }, J = {
        init: function () {
            var t = Y();
            if (this.params.history) {
                if (!t.history || !t.history.pushState) return this.params.history.enabled = !1, void (this.params.hashNavigation.enabled = !0);
                var e = this.history;
                e.initialized = !0, e.paths = J.getPathValues(this.params.url), (e.paths.key || e.paths.value) && (e.scrollToSlide(0, e.paths.value, this.params.runCallbacksOnInit), this.params.history.replaceState || t.addEventListener("popstate", this.history.setHistoryPopState))
            }
        }, destroy: function () {
            var t = Y();
            this.params.history.replaceState || t.removeEventListener("popstate", this.history.setHistoryPopState)
        }, setHistoryPopState: function () {
            this.history.paths = J.getPathValues(this.params.url), this.history.scrollToSlide(this.params.speed, this.history.paths.value, !1)
        }, getPathValues: function (t) {
            var e = Y(), t = (t ? new URL(t) : e.location).pathname.slice(1).split("/").filter(function (t) {
                return "" !== t
            }), e = t.length;
            return {key: t[e - 2], value: t[e - 1]}
        }, setHistory: function (t, e) {
            var i, n = Y();
            this.history.initialized && this.params.history.enabled && (i = this.params.url ? new URL(this.params.url) : n.location, e = this.slides.eq(e), e = J.slugify(e.attr("data-history")), i.pathname.includes(t) || (e = t + "/" + e), (t = n.history.state) && t.value === e || (this.params.history.replaceState ? n.history.replaceState({value: e}, null, e) : n.history.pushState({value: e}, null, e)))
        }, slugify: function (t) {
            return t.toString().replace(/\s+/g, "-").replace(/[^\w-]+/g, "").replace(/--+/g, "-").replace(/^-+/, "").replace(/-+$/, "")
        }, scrollToSlide: function (t, e, i) {
            if (e) for (var n = 0, o = this.slides.length; n < o; n += 1) {
                var s = this.slides.eq(n);
                J.slugify(s.attr("data-history")) !== e || s.hasClass(this.params.slideDuplicateClass) || (s = s.index(), this.slideTo(s, t, i))
            } else this.slideTo(0, t, i)
        }
    }, tt = {
        onHashCange: function () {
            var t = v();
            this.emit("hashChange");
            var t = t.location.hash.replace("#", "");
            t === this.slides.eq(this.activeIndex).attr("data-hash") || void 0 !== (t = this.$wrapperEl.children("." + this.params.slideClass + '[data-hash="' + t + '"]').index()) && this.slideTo(t)
        }, setHash: function () {
            var t = Y(), e = v();
            this.hashNavigation.initialized && this.params.hashNavigation.enabled && (this.params.hashNavigation.replaceState && t.history && t.history.replaceState ? t.history.replaceState(null, null, "#" + this.slides.eq(this.activeIndex).attr("data-hash") || "") : (t = (t = this.slides.eq(this.activeIndex)).attr("data-hash") || t.attr("data-history"), e.location.hash = t || ""), this.emit("hashSet"))
        }, init: function () {
            var t = v(), e = Y();
            if (!(!this.params.hashNavigation.enabled || this.params.history && this.params.history.enabled)) {
                this.hashNavigation.initialized = !0;
                var i = t.location.hash.replace("#", "");
                if (i) for (var n = 0, o = this.slides.length; n < o; n += 1) {
                    var s = this.slides.eq(n);
                    (s.attr("data-hash") || s.attr("data-history")) !== i || s.hasClass(this.params.slideDuplicateClass) || (s = s.index(), this.slideTo(s, 0, this.params.runCallbacksOnInit, !0))
                }
                this.params.hashNavigation.watchState && C(e).on("hashchange", this.hashNavigation.onHashCange)
            }
        }, destroy: function () {
            var t = Y();
            this.params.hashNavigation.watchState && C(t).off("hashchange", this.hashNavigation.onHashCange)
        }
    }, et = {
        run: function () {
            var t = this, e = t.slides.eq(t.activeIndex), i = t.params.autoplay.delay;
            e.attr("data-swiper-autoplay") && (i = e.attr("data-swiper-autoplay") || t.params.autoplay.delay), clearTimeout(t.autoplay.timeout), t.autoplay.timeout = S(function () {
                t.params.autoplay.reverseDirection ? t.params.loop ? (t.loopFix(), t.slidePrev(t.params.speed, !0, !0), t.emit("autoplay")) : t.isBeginning ? t.params.autoplay.stopOnLastSlide ? t.autoplay.stop() : (t.slideTo(t.slides.length - 1, t.params.speed, !0, !0), t.emit("autoplay")) : (t.slidePrev(t.params.speed, !0, !0), t.emit("autoplay")) : t.params.loop ? (t.loopFix(), t.slideNext(t.params.speed, !0, !0), t.emit("autoplay")) : t.isEnd ? t.params.autoplay.stopOnLastSlide ? t.autoplay.stop() : (t.slideTo(0, t.params.speed, !0, !0), t.emit("autoplay")) : (t.slideNext(t.params.speed, !0, !0), t.emit("autoplay")), t.params.cssMode && t.autoplay.running && t.autoplay.run()
            }, i)
        }, start: function () {
            return void 0 === this.autoplay.timeout && !this.autoplay.running && (this.autoplay.running = !0, this.emit("autoplayStart"), this.autoplay.run(), !0)
        }, stop: function () {
            return !!this.autoplay.running && void 0 !== this.autoplay.timeout && (this.autoplay.timeout && (clearTimeout(this.autoplay.timeout), this.autoplay.timeout = void 0), this.autoplay.running = !1, this.emit("autoplayStop"), !0)
        }, pause: function (t) {
            this.autoplay.running && (this.autoplay.paused || (this.autoplay.timeout && clearTimeout(this.autoplay.timeout), this.autoplay.paused = !0, 0 !== t && this.params.autoplay.waitForTransition ? (this.$wrapperEl[0].addEventListener("transitionend", this.autoplay.onTransitionEnd), this.$wrapperEl[0].addEventListener("webkitTransitionEnd", this.autoplay.onTransitionEnd)) : (this.autoplay.paused = !1, this.autoplay.run())))
        }, onVisibilityChange: function () {
            var t = v();
            "hidden" === t.visibilityState && this.autoplay.running && this.autoplay.pause(), "visible" === t.visibilityState && this.autoplay.paused && (this.autoplay.run(), this.autoplay.paused = !1)
        }, onTransitionEnd: function (t) {
            this && !this.destroyed && this.$wrapperEl && t.target === this.$wrapperEl[0] && (this.$wrapperEl[0].removeEventListener("transitionend", this.autoplay.onTransitionEnd), this.$wrapperEl[0].removeEventListener("webkitTransitionEnd", this.autoplay.onTransitionEnd), this.autoplay.paused = !1, this.autoplay.running ? this.autoplay.run() : this.autoplay.stop())
        }
    }, it = {
        setTranslate: function () {
            for (var t = this.slides, e = 0; e < t.length; e += 1) {
                var i = this.slides.eq(e), n = -i[0].swiperSlideOffset;
                this.params.virtualTranslate || (n -= this.translate);
                var o = 0;
                this.isHorizontal() || (o = n, n = 0);
                var s = this.params.fadeEffect.crossFade ? Math.max(1 - Math.abs(i[0].progress), 0) : 1 + Math.min(Math.max(i[0].progress, -1), 0);
                i.css({opacity: s}).transform("translate3d(" + n + "px, " + o + "px, 0px)")
            }
        }, setTransition: function (t) {
            var i, n = this, e = n.slides, o = n.$wrapperEl;
            e.transition(t), n.params.virtualTranslate && 0 !== t && (i = !1, e.transitionEnd(function () {
                if (!i && n && !n.destroyed) {
                    i = !0, n.animating = !1;
                    for (var t = ["webkitTransitionEnd", "transitionend"], e = 0; e < t.length; e += 1) o.trigger(t[e])
                }
            }))
        }
    }, nt = {
        setTranslate: function () {
            var t, e = this.$el, i = this.$wrapperEl, n = this.slides, o = this.width, s = this.height,
                a = this.rtlTranslate, r = this.size, l = this.browser, c = this.params.cubeEffect,
                d = this.isHorizontal(), u = this.virtual && this.params.virtual.enabled, h = 0;
            c.shadow && (d ? (0 === (t = i.find(".swiper-cube-shadow")).length && (t = C('<div class="swiper-cube-shadow"></div>'), i.append(t)), t.css({height: o + "px"})) : 0 === (t = e.find(".swiper-cube-shadow")).length && (t = C('<div class="swiper-cube-shadow"></div>'), e.append(t)));
            for (var p, f = 0; f < n.length; f += 1) {
                var m = n.eq(f), g = f, v = 90 * (g = u ? parseInt(m.attr("data-swiper-slide-index"), 10) : g),
                    b = Math.floor(v / 360);
                a && (v = -v, b = Math.floor(-v / 360));
                var y = Math.max(Math.min(m[0].progress, 1), -1), w = 0, x = 0, _ = 0;
                g % 4 == 0 ? (w = 4 * -b * r, _ = 0) : (g - 1) % 4 == 0 ? (w = 0, _ = 4 * -b * r) : (g - 2) % 4 == 0 ? (w = r + 4 * b * r, _ = r) : (g - 3) % 4 == 0 && (w = -r, _ = 3 * r + 4 * r * b), a && (w = -w), d || (x = w, w = 0), y <= 1 && -1 < y && (h = a ? 90 * -g - 90 * y : 90 * g + 90 * y), m.transform("rotateX(" + (d ? 0 : -v) + "deg) rotateY(" + (d ? v : 0) + "deg) translate3d(" + w + "px, " + x + "px, " + _ + "px)"), c.slideShadows && (x = d ? m.find(".swiper-slide-shadow-left") : m.find(".swiper-slide-shadow-top"), _ = d ? m.find(".swiper-slide-shadow-right") : m.find(".swiper-slide-shadow-bottom"), 0 === x.length && (x = C('<div class="swiper-slide-shadow-' + (d ? "left" : "top") + '"></div>'), m.append(x)), 0 === _.length && (_ = C('<div class="swiper-slide-shadow-' + (d ? "right" : "bottom") + '"></div>'), m.append(_)), x.length && (x[0].style.opacity = Math.max(-y, 0)), _.length && (_[0].style.opacity = Math.max(y, 0)))
            }
            i.css({
                "-webkit-transform-origin": "50% 50% -" + r / 2 + "px",
                "-moz-transform-origin": "50% 50% -" + r / 2 + "px",
                "-ms-transform-origin": "50% 50% -" + r / 2 + "px",
                "transform-origin": "50% 50% -" + r / 2 + "px"
            }), c.shadow && (d ? t.transform("translate3d(0px, " + (o / 2 + c.shadowOffset) + "px, " + -o / 2 + "px) rotateX(90deg) rotateZ(0deg) scale(" + c.shadowScale + ")") : (p = Math.abs(h) - 90 * Math.floor(Math.abs(h) / 90), e = 1.5 - (Math.sin(2 * p * Math.PI / 360) / 2 + Math.cos(2 * p * Math.PI / 360) / 2), o = c.shadowScale, p = c.shadowScale / e, e = c.shadowOffset, t.transform("scale3d(" + o + ", 1, " + p + ") translate3d(0px, " + (s / 2 + e) + "px, " + -s / 2 / p + "px) rotateX(-90deg)")));
            l = l.isSafari || l.isWebView ? -r / 2 : 0;
            i.transform("translate3d(0px,0," + l + "px) rotateX(" + (this.isHorizontal() ? 0 : h) + "deg) rotateY(" + (this.isHorizontal() ? -h : 0) + "deg)")
        }, setTransition: function (t) {
            var e = this.$el;
            this.slides.transition(t).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(t), this.params.cubeEffect.shadow && !this.isHorizontal() && e.find(".swiper-cube-shadow").transition(t)
        }
    }, ot = {
        setTranslate: function () {
            for (var t = this.slides, e = this.rtlTranslate, i = 0; i < t.length; i += 1) {
                var n, o, s = t.eq(i), a = s[0].progress,
                    r = -180 * (a = this.params.flipEffect.limitRotation ? Math.max(Math.min(s[0].progress, 1), -1) : a),
                    l = 0, c = -s[0].swiperSlideOffset, d = 0;
                this.isHorizontal() ? e && (r = -r) : (d = c, l = -r, r = c = 0), s[0].style.zIndex = -Math.abs(Math.round(a)) + t.length, this.params.flipEffect.slideShadows && (n = this.isHorizontal() ? s.find(".swiper-slide-shadow-left") : s.find(".swiper-slide-shadow-top"), o = this.isHorizontal() ? s.find(".swiper-slide-shadow-right") : s.find(".swiper-slide-shadow-bottom"), 0 === n.length && (n = C('<div class="swiper-slide-shadow-' + (this.isHorizontal() ? "left" : "top") + '"></div>'), s.append(n)), 0 === o.length && (o = C('<div class="swiper-slide-shadow-' + (this.isHorizontal() ? "right" : "bottom") + '"></div>'), s.append(o)), n.length && (n[0].style.opacity = Math.max(-a, 0)), o.length && (o[0].style.opacity = Math.max(a, 0))), s.transform("translate3d(" + c + "px, " + d + "px, 0px) rotateX(" + l + "deg) rotateY(" + r + "deg)")
            }
        }, setTransition: function (t) {
            var i, n = this, e = n.slides, o = n.activeIndex, s = n.$wrapperEl;
            e.transition(t).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(t), n.params.virtualTranslate && 0 !== t && (i = !1, e.eq(o).transitionEnd(function () {
                if (!i && n && !n.destroyed) {
                    i = !0, n.animating = !1;
                    for (var t = ["webkitTransitionEnd", "transitionend"], e = 0; e < t.length; e += 1) s.trigger(t[e])
                }
            }))
        }
    }, st = {
        setTranslate: function () {
            for (var t = this.width, e = this.height, i = this.slides, n = this.slidesSizesGrid, o = this.params.coverflowEffect, s = this.isHorizontal(), a = this.translate, r = s ? t / 2 - a : e / 2 - a, l = s ? o.rotate : -o.rotate, c = o.depth, d = 0, u = i.length; d < u; d += 1) {
                var h = i.eq(d), p = n[d], f = (r - h[0].swiperSlideOffset - p / 2) / p * o.modifier, m = s ? l * f : 0,
                    g = s ? 0 : l * f, v = -c * Math.abs(f), b = o.stretch;
                "string" == typeof b && -1 !== b.indexOf("%") && (b = parseFloat(o.stretch) / 100 * p);
                var y = s ? 0 : b * f, p = s ? b * f : 0, b = 1 - (1 - o.scale) * Math.abs(f);
                Math.abs(p) < .001 && (p = 0), Math.abs(y) < .001 && (y = 0), Math.abs(v) < .001 && (v = 0), Math.abs(m) < .001 && (m = 0), Math.abs(g) < .001 && (g = 0), Math.abs(b) < .001 && (b = 0), h.transform("translate3d(" + p + "px," + y + "px," + v + "px)  rotateX(" + g + "deg) rotateY(" + m + "deg) scale(" + b + ")"), h[0].style.zIndex = 1 - Math.abs(Math.round(f)), o.slideShadows && (m = s ? h.find(".swiper-slide-shadow-left") : h.find(".swiper-slide-shadow-top"), b = s ? h.find(".swiper-slide-shadow-right") : h.find(".swiper-slide-shadow-bottom"), 0 === m.length && (m = C('<div class="swiper-slide-shadow-' + (s ? "left" : "top") + '"></div>'), h.append(m)), 0 === b.length && (b = C('<div class="swiper-slide-shadow-' + (s ? "right" : "bottom") + '"></div>'), h.append(b)), m.length && (m[0].style.opacity = 0 < f ? f : 0), b.length && (b[0].style.opacity = 0 < -f ? -f : 0))
            }
        }, setTransition: function (t) {
            this.slides.transition(t).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(t)
        }
    }, at = {
        init: function () {
            var t = this.params.thumbs;
            if (this.thumbs.initialized) return !1;
            this.thumbs.initialized = !0;
            var e = this.constructor;
            return t.swiper instanceof e ? (this.thumbs.swiper = t.swiper, X(this.thumbs.swiper.originalParams, {
                watchSlidesProgress: !0,
                slideToClickedSlide: !1
            }), X(this.thumbs.swiper.params, {
                watchSlidesProgress: !0,
                slideToClickedSlide: !1
            })) : y(t.swiper) && (this.thumbs.swiper = new e(X({}, t.swiper, {
                watchSlidesVisibility: !0,
                watchSlidesProgress: !0,
                slideToClickedSlide: !1
            })), this.thumbs.swiperCreated = !0), this.thumbs.swiper.$el.addClass(this.params.thumbs.thumbsContainerClass), this.thumbs.swiper.on("tap", this.thumbs.onThumbClick), !0
        }, onThumbClick: function () {
            var t, e, i, n = this.thumbs.swiper;
            n && (e = n.clickedIndex, (t = n.clickedSlide) && C(t).hasClass(this.params.thumbs.slideThumbActiveClass) || null == e || (i = n.params.loop ? parseInt(C(n.clickedSlide).attr("data-swiper-slide-index"), 10) : e, this.params.loop && (t = this.activeIndex, this.slides.eq(t).hasClass(this.params.slideDuplicateClass) && (this.loopFix(), this._clientLeft = this.$wrapperEl[0].clientLeft, t = this.activeIndex), n = this.slides.eq(t).prevAll('[data-swiper-slide-index="' + i + '"]').eq(0).index(), e = this.slides.eq(t).nextAll('[data-swiper-slide-index="' + i + '"]').eq(0).index(), i = void 0 === n || void 0 !== e && e - t < t - n ? e : n), this.slideTo(i)))
        }, update: function (t) {
            var e = this.thumbs.swiper;
            if (e) {
                var i, n, o, s = "auto" === e.params.slidesPerView ? e.slidesPerViewDynamic() : e.params.slidesPerView,
                    a = this.params.thumbs.autoScrollOffset, r = a && !e.params.loop;
                this.realIndex === e.realIndex && !r || (i = e.activeIndex, o = e.params.loop ? (e.slides.eq(i).hasClass(e.params.slideDuplicateClass) && (e.loopFix(), e._clientLeft = e.$wrapperEl[0].clientLeft, i = e.activeIndex), o = e.slides.eq(i).prevAll('[data-swiper-slide-index="' + this.realIndex + '"]').eq(0).index(), n = e.slides.eq(i).nextAll('[data-swiper-slide-index="' + this.realIndex + '"]').eq(0).index(), n = void 0 === o ? n : void 0 === n ? o : n - i == i - o ? i : n - i < i - o ? n : o, this.activeIndex > this.previousIndex ? "next" : "prev") : (n = this.realIndex) > this.previousIndex ? "next" : "prev", r && (n += "next" === o ? a : -1 * a), e.visibleSlidesIndexes && e.visibleSlidesIndexes.indexOf(n) < 0 && (e.params.centeredSlides ? n = i < n ? n - Math.floor(s / 2) + 1 : n + Math.floor(s / 2) - 1 : i < n && (n = n - s + 1), e.slideTo(n, t ? 0 : void 0)));
                var l = 1, c = this.params.thumbs.slideThumbActiveClass;
                if (1 < this.params.slidesPerView && !this.params.centeredSlides && (l = this.params.slidesPerView), this.params.thumbs.multipleActiveThumbs || (l = 1), l = Math.floor(l), e.slides.removeClass(c), e.params.loop || e.params.virtual && e.params.virtual.enabled) for (var d = 0; d < l; d += 1) e.$wrapperEl.children('[data-swiper-slide-index="' + (this.realIndex + d) + '"]').addClass(c); else for (var u = 0; u < l; u += 1) e.slides.eq(this.realIndex + u).addClass(c)
            }
        }
    };
    return H.use([E, I, {
        name: "mousewheel",
        params: {
            mousewheel: {
                enabled: !1,
                releaseOnEdges: !1,
                invert: !1,
                forceToAxis: !1,
                sensitivity: 1,
                eventsTarget: "container",
                thresholdDelta: null,
                thresholdTime: null
            }
        },
        create: function () {
            w(this, {
                mousewheel: {
                    enabled: !1,
                    lastScrollTime: T(),
                    lastEventBeforeSnap: void 0,
                    recentWheelEvents: [],
                    enable: W.enable,
                    disable: W.disable,
                    handle: W.handle,
                    handleMouseEnter: W.handleMouseEnter,
                    handleMouseLeave: W.handleMouseLeave,
                    animateSlider: W.animateSlider,
                    releaseScroll: W.releaseScroll
                }
            })
        },
        on: {
            init: function (t) {
                !t.params.mousewheel.enabled && t.params.cssMode && t.mousewheel.disable(), t.params.mousewheel.enabled && t.mousewheel.enable()
            }, destroy: function (t) {
                t.params.cssMode && t.mousewheel.enable(), t.mousewheel.enabled && t.mousewheel.disable()
            }
        }
    }, {
        name: "navigation",
        params: {
            navigation: {
                nextEl: null,
                prevEl: null,
                hideOnClick: !1,
                disabledClass: "swiper-button-disabled",
                hiddenClass: "swiper-button-hidden",
                lockClass: "swiper-button-lock"
            }
        },
        create: function () {
            w(this, {navigation: e({}, F)})
        },
        on: {
            init: function (t) {
                t.navigation.init(), t.navigation.update()
            }, toEdge: function (t) {
                t.navigation.update()
            }, fromEdge: function (t) {
                t.navigation.update()
            }, destroy: function (t) {
                t.navigation.destroy()
            }, click: function (t, e) {
                var i, n = t.navigation, o = n.$nextEl, n = n.$prevEl;
                !t.params.navigation.hideOnClick || C(e.target).is(n) || C(e.target).is(o) || (o ? i = o.hasClass(t.params.navigation.hiddenClass) : n && (i = n.hasClass(t.params.navigation.hiddenClass)), !0 === i ? t.emit("navigationShow") : t.emit("navigationHide"), o && o.toggleClass(t.params.navigation.hiddenClass), n && n.toggleClass(t.params.navigation.hiddenClass))
            }
        }
    }, {
        name: "pagination",
        params: {
            pagination: {
                el: null,
                bulletElement: "span",
                clickable: !1,
                hideOnClick: !1,
                renderBullet: null,
                renderProgressbar: null,
                renderFraction: null,
                renderCustom: null,
                progressbarOpposite: !1,
                type: "bullets",
                dynamicBullets: !1,
                dynamicMainBullets: 1,
                formatFractionCurrent: function (t) {
                    return t
                },
                formatFractionTotal: function (t) {
                    return t
                },
                bulletClass: "swiper-pagination-bullet",
                bulletActiveClass: "swiper-pagination-bullet-active",
                modifierClass: "swiper-pagination-",
                currentClass: "swiper-pagination-current",
                totalClass: "swiper-pagination-total",
                hiddenClass: "swiper-pagination-hidden",
                progressbarFillClass: "swiper-pagination-progressbar-fill",
                progressbarOppositeClass: "swiper-pagination-progressbar-opposite",
                clickableClass: "swiper-pagination-clickable",
                lockClass: "swiper-pagination-lock"
            }
        },
        create: function () {
            w(this, {pagination: e({dynamicBulletIndex: 0}, q)})
        },
        on: {
            init: function (t) {
                t.pagination.init(), t.pagination.render(), t.pagination.update()
            }, activeIndexChange: function (t) {
                !t.params.loop && void 0 !== t.snapIndex || t.pagination.update()
            }, snapIndexChange: function (t) {
                t.params.loop || t.pagination.update()
            }, slidesLengthChange: function (t) {
                t.params.loop && (t.pagination.render(), t.pagination.update())
            }, snapGridLengthChange: function (t) {
                t.params.loop || (t.pagination.render(), t.pagination.update())
            }, destroy: function (t) {
                t.pagination.destroy()
            }, click: function (t, e) {
                t.params.pagination.el && t.params.pagination.hideOnClick && 0 < t.pagination.$el.length && !C(e.target).hasClass(t.params.pagination.bulletClass) && (!0 === t.pagination.$el.hasClass(t.params.pagination.hiddenClass) ? t.emit("paginationShow") : t.emit("paginationHide"), t.pagination.$el.toggleClass(t.params.pagination.hiddenClass))
            }
        }
    }, {
        name: "scrollbar",
        params: {
            scrollbar: {
                el: null,
                dragSize: "auto",
                hide: !1,
                draggable: !1,
                snapOnRelease: !0,
                lockClass: "swiper-scrollbar-lock",
                dragClass: "swiper-scrollbar-drag"
            }
        },
        create: function () {
            w(this, {scrollbar: e({isTouched: !1, timeout: null, dragTimeout: null}, V)})
        },
        on: {
            init: function (t) {
                t.scrollbar.init(), t.scrollbar.updateSize(), t.scrollbar.setTranslate()
            }, update: function (t) {
                t.scrollbar.updateSize()
            }, resize: function (t) {
                t.scrollbar.updateSize()
            }, observerUpdate: function (t) {
                t.scrollbar.updateSize()
            }, setTranslate: function (t) {
                t.scrollbar.setTranslate()
            }, setTransition: function (t, e) {
                t.scrollbar.setTransition(e)
            }, destroy: function (t) {
                t.scrollbar.destroy()
            }
        }
    }, {
        name: "parallax", params: {parallax: {enabled: !1}}, create: function () {
            w(this, {parallax: e({}, U)})
        }, on: {
            beforeInit: function (t) {
                t.params.parallax.enabled && (t.params.watchSlidesProgress = !0, t.originalParams.watchSlidesProgress = !0)
            }, init: function (t) {
                t.params.parallax.enabled && t.parallax.setTranslate()
            }, setTranslate: function (t) {
                t.params.parallax.enabled && t.parallax.setTranslate()
            }, setTransition: function (t, e) {
                t.params.parallax.enabled && t.parallax.setTransition(e)
            }
        }
    }, {
        name: "zoom",
        params: {
            zoom: {
                enabled: !1,
                maxRatio: 3,
                minRatio: 1,
                toggle: !0,
                containerClass: "swiper-zoom-container",
                zoomedSlideClass: "swiper-slide-zoomed"
            }
        },
        create: function () {
            var n = this;
            w(n, {
                zoom: e({
                    enabled: !1,
                    scale: 1,
                    currentScale: 1,
                    isScaling: !1,
                    gesture: {
                        $slideEl: void 0,
                        slideWidth: void 0,
                        slideHeight: void 0,
                        $imageEl: void 0,
                        $imageWrapEl: void 0,
                        maxRatio: 3
                    },
                    image: {
                        isTouched: void 0,
                        isMoved: void 0,
                        currentX: void 0,
                        currentY: void 0,
                        minX: void 0,
                        minY: void 0,
                        maxX: void 0,
                        maxY: void 0,
                        width: void 0,
                        height: void 0,
                        startX: void 0,
                        startY: void 0,
                        touchesStart: {},
                        touchesCurrent: {}
                    },
                    velocity: {x: void 0, y: void 0, prevPositionX: void 0, prevPositionY: void 0, prevTime: void 0}
                }, G)
            });
            var o = 1;
            Object.defineProperty(n.zoom, "scale", {
                get: function () {
                    return o
                }, set: function (t) {
                    var e, i;
                    o !== t && (e = n.zoom.gesture.$imageEl ? n.zoom.gesture.$imageEl[0] : void 0, i = n.zoom.gesture.$slideEl ? n.zoom.gesture.$slideEl[0] : void 0, n.emit("zoomChange", t, e, i)), o = t
                }
            })
        },
        on: {
            init: function (t) {
                t.params.zoom.enabled && t.zoom.enable()
            }, destroy: function (t) {
                t.zoom.disable()
            }, touchStart: function (t, e) {
                t.zoom.enabled && t.zoom.onTouchStart(e)
            }, touchEnd: function (t, e) {
                t.zoom.enabled && t.zoom.onTouchEnd(e)
            }, doubleTap: function (t, e) {
                t.params.zoom.enabled && t.zoom.enabled && t.params.zoom.toggle && t.zoom.toggle(e)
            }, transitionEnd: function (t) {
                t.zoom.enabled && t.params.zoom.enabled && t.zoom.onTransitionEnd()
            }, slideChange: function (t) {
                t.zoom.enabled && t.params.zoom.enabled && t.params.cssMode && t.zoom.onTransitionEnd()
            }
        }
    }, {
        name: "lazy",
        params: {
            lazy: {
                enabled: !1,
                loadPrevNext: !1,
                loadPrevNextAmount: 1,
                loadOnTransitionStart: !1,
                elementClass: "swiper-lazy",
                loadingClass: "swiper-lazy-loading",
                loadedClass: "swiper-lazy-loaded",
                preloaderClass: "swiper-lazy-preloader"
            }
        },
        create: function () {
            w(this, {lazy: e({initialImageLoaded: !1}, K)})
        },
        on: {
            beforeInit: function (t) {
                t.params.lazy.enabled && t.params.preloadImages && (t.params.preloadImages = !1)
            }, init: function (t) {
                t.params.lazy.enabled && !t.params.loop && 0 === t.params.initialSlide && t.lazy.load()
            }, scroll: function (t) {
                t.params.freeMode && !t.params.freeModeSticky && t.lazy.load()
            }, resize: function (t) {
                t.params.lazy.enabled && t.lazy.load()
            }, scrollbarDragMove: function (t) {
                t.params.lazy.enabled && t.lazy.load()
            }, transitionStart: function (t) {
                t.params.lazy.enabled && (t.params.lazy.loadOnTransitionStart || !t.params.lazy.loadOnTransitionStart && !t.lazy.initialImageLoaded) && t.lazy.load()
            }, transitionEnd: function (t) {
                t.params.lazy.enabled && !t.params.lazy.loadOnTransitionStart && t.lazy.load()
            }, slideChange: function (t) {
                t.params.lazy.enabled && t.params.cssMode && t.lazy.load()
            }
        }
    }, {
        name: "controller", params: {controller: {control: void 0, inverse: !1, by: "slide"}}, create: function () {
            w(this, {controller: e({control: this.params.controller.control}, Z)})
        }, on: {
            update: function (t) {
                t.controller.control && t.controller.spline && (t.controller.spline = void 0, delete t.controller.spline)
            }, resize: function (t) {
                t.controller.control && t.controller.spline && (t.controller.spline = void 0, delete t.controller.spline)
            }, observerUpdate: function (t) {
                t.controller.control && t.controller.spline && (t.controller.spline = void 0, delete t.controller.spline)
            }, setTranslate: function (t, e, i) {
                t.controller.control && t.controller.setTranslate(e, i)
            }, setTransition: function (t, e, i) {
                t.controller.control && t.controller.setTransition(e, i)
            }
        }
    }, {
        name: "a11y",
        params: {
            a11y: {
                enabled: !0,
                notificationClass: "swiper-notification",
                prevSlideMessage: "Previous slide",
                nextSlideMessage: "Next slide",
                firstSlideMessage: "This is the first slide",
                lastSlideMessage: "This is the last slide",
                paginationBulletMessage: "Go to slide {{index}}"
            }
        },
        create: function () {
            w(this, {a11y: e(e({}, Q), {}, {liveRegion: C('<span class="' + this.params.a11y.notificationClass + '" aria-live="assertive" aria-atomic="true"></span>')})})
        },
        on: {
            init: function (t) {
                t.params.a11y.enabled && (t.a11y.init(), t.a11y.updateNavigation())
            }, toEdge: function (t) {
                t.params.a11y.enabled && t.a11y.updateNavigation()
            }, fromEdge: function (t) {
                t.params.a11y.enabled && t.a11y.updateNavigation()
            }, paginationUpdate: function (t) {
                t.params.a11y.enabled && t.a11y.updatePagination()
            }, destroy: function (t) {
                t.params.a11y.enabled && t.a11y.destroy()
            }
        }
    }, {
        name: "history", params: {history: {enabled: !1, replaceState: !1, key: "slides"}}, create: function () {
            w(this, {history: e({}, J)})
        }, on: {
            init: function (t) {
                t.params.history.enabled && t.history.init()
            }, destroy: function (t) {
                t.params.history.enabled && t.history.destroy()
            }, transitionEnd: function (t) {
                t.history.initialized && t.history.setHistory(t.params.history.key, t.activeIndex)
            }, slideChange: function (t) {
                t.history.initialized && t.params.cssMode && t.history.setHistory(t.params.history.key, t.activeIndex)
            }
        }
    }, {
        name: "hash-navigation",
        params: {hashNavigation: {enabled: !1, replaceState: !1, watchState: !1}},
        create: function () {
            w(this, {hashNavigation: e({initialized: !1}, tt)})
        },
        on: {
            init: function (t) {
                t.params.hashNavigation.enabled && t.hashNavigation.init()
            }, destroy: function (t) {
                t.params.hashNavigation.enabled && t.hashNavigation.destroy()
            }, transitionEnd: function (t) {
                t.hashNavigation.initialized && t.hashNavigation.setHash()
            }, slideChange: function (t) {
                t.hashNavigation.initialized && t.params.cssMode && t.hashNavigation.setHash()
            }
        }
    }, {
        name: "autoplay",
        params: {
            autoplay: {
                enabled: !1,
                delay: 3e3,
                waitForTransition: !0,
                disableOnInteraction: !0,
                stopOnLastSlide: !1,
                reverseDirection: !1
            }
        },
        create: function () {
            w(this, {autoplay: e(e({}, et), {}, {running: !1, paused: !1})})
        },
        on: {
            init: function (t) {
                t.params.autoplay.enabled && (t.autoplay.start(), v().addEventListener("visibilitychange", t.autoplay.onVisibilityChange))
            }, beforeTransitionStart: function (t, e, i) {
                t.autoplay.running && (i || !t.params.autoplay.disableOnInteraction ? t.autoplay.pause(e) : t.autoplay.stop())
            }, sliderFirstMove: function (t) {
                t.autoplay.running && (t.params.autoplay.disableOnInteraction ? t.autoplay.stop() : t.autoplay.pause())
            }, touchEnd: function (t) {
                t.params.cssMode && t.autoplay.paused && !t.params.autoplay.disableOnInteraction && t.autoplay.run()
            }, destroy: function (t) {
                t.autoplay.running && t.autoplay.stop(), v().removeEventListener("visibilitychange", t.autoplay.onVisibilityChange)
            }
        }
    }, {
        name: "effect-fade", params: {fadeEffect: {crossFade: !1}}, create: function () {
            w(this, {fadeEffect: e({}, it)})
        }, on: {
            beforeInit: function (t) {
                var e;
                "fade" === t.params.effect && (t.classNames.push(t.params.containerModifierClass + "fade"), X(t.params, e = {
                    slidesPerView: 1,
                    slidesPerColumn: 1,
                    slidesPerGroup: 1,
                    watchSlidesProgress: !0,
                    spaceBetween: 0,
                    virtualTranslate: !0
                }), X(t.originalParams, e))
            }, setTranslate: function (t) {
                "fade" === t.params.effect && t.fadeEffect.setTranslate()
            }, setTransition: function (t, e) {
                "fade" === t.params.effect && t.fadeEffect.setTransition(e)
            }
        }
    }, {
        name: "effect-cube",
        params: {cubeEffect: {slideShadows: !0, shadow: !0, shadowOffset: 20, shadowScale: .94}},
        create: function () {
            w(this, {cubeEffect: e({}, nt)})
        },
        on: {
            beforeInit: function (t) {
                var e;
                "cube" === t.params.effect && (t.classNames.push(t.params.containerModifierClass + "cube"), t.classNames.push(t.params.containerModifierClass + "3d"), X(t.params, e = {
                    slidesPerView: 1,
                    slidesPerColumn: 1,
                    slidesPerGroup: 1,
                    watchSlidesProgress: !0,
                    resistanceRatio: 0,
                    spaceBetween: 0,
                    centeredSlides: !1,
                    virtualTranslate: !0
                }), X(t.originalParams, e))
            }, setTranslate: function (t) {
                "cube" === t.params.effect && t.cubeEffect.setTranslate()
            }, setTransition: function (t, e) {
                "cube" === t.params.effect && t.cubeEffect.setTransition(e)
            }
        }
    }, {
        name: "effect-flip", params: {flipEffect: {slideShadows: !0, limitRotation: !0}}, create: function () {
            w(this, {flipEffect: e({}, ot)})
        }, on: {
            beforeInit: function (t) {
                var e;
                "flip" === t.params.effect && (t.classNames.push(t.params.containerModifierClass + "flip"), t.classNames.push(t.params.containerModifierClass + "3d"), X(t.params, e = {
                    slidesPerView: 1,
                    slidesPerColumn: 1,
                    slidesPerGroup: 1,
                    watchSlidesProgress: !0,
                    spaceBetween: 0,
                    virtualTranslate: !0
                }), X(t.originalParams, e))
            }, setTranslate: function (t) {
                "flip" === t.params.effect && t.flipEffect.setTranslate()
            }, setTransition: function (t, e) {
                "flip" === t.params.effect && t.flipEffect.setTransition(e)
            }
        }
    }, {
        name: "effect-coverflow",
        params: {coverflowEffect: {rotate: 50, stretch: 0, depth: 100, scale: 1, modifier: 1, slideShadows: !0}},
        create: function () {
            w(this, {coverflowEffect: e({}, st)})
        },
        on: {
            beforeInit: function (t) {
                "coverflow" === t.params.effect && (t.classNames.push(t.params.containerModifierClass + "coverflow"), t.classNames.push(t.params.containerModifierClass + "3d"), t.params.watchSlidesProgress = !0, t.originalParams.watchSlidesProgress = !0)
            }, setTranslate: function (t) {
                "coverflow" === t.params.effect && t.coverflowEffect.setTranslate()
            }, setTransition: function (t, e) {
                "coverflow" === t.params.effect && t.coverflowEffect.setTransition(e)
            }
        }
    }, {
        name: "thumbs",
        params: {
            thumbs: {
                swiper: null,
                multipleActiveThumbs: !0,
                autoScrollOffset: 0,
                slideThumbActiveClass: "swiper-slide-thumb-active",
                thumbsContainerClass: "swiper-container-thumbs"
            }
        },
        create: function () {
            w(this, {thumbs: e({swiper: null, initialized: !1}, at)})
        },
        on: {
            beforeInit: function (t) {
                var e = t.params.thumbs;
                e && e.swiper && (t.thumbs.init(), t.thumbs.update(!0))
            }, slideChange: function (t) {
                t.thumbs.swiper && t.thumbs.update()
            }, update: function (t) {
                t.thumbs.swiper && t.thumbs.update()
            }, resize: function (t) {
                t.thumbs.swiper && t.thumbs.update()
            }, observerUpdate: function (t) {
                t.thumbs.swiper && t.thumbs.update()
            }, setTransition: function (t, e) {
                t = t.thumbs.swiper;
                t && t.setTransition(e)
            }, beforeDestroy: function (t) {
                var e = t.thumbs.swiper;
                e && t.thumbs.swiperCreated && e && e.destroy()
            }
        }
    }]), H
}), function (i) {
    "function" == typeof define && define.amd ? define(["jquery"], i) : "object" == typeof module && module.exports ? module.exports = function (t, e) {
        return void 0 === e && (e = "undefined" != typeof window ? require("jquery") : require("jquery")(t)), i(e), e
    } : i(jQuery)
}(function (e) {
    var t, i, h, s, a, p, f, m, g, v, b, n, o, y,
        r = ((l = e && e.fn && e.fn.select2 && e.fn.select2.amd ? e.fn.select2.amd : l) && l.requirejs || (l ? i = l : l = {}, m = {}, g = {}, v = {}, b = {}, n = Object.prototype.hasOwnProperty, o = [].slice, y = /\.js$/, p = function (t, e) {
            var i, n, o = d(t), s = o[0], e = e[1];
            return t = o[1], s && (i = _(s = c(s, e))), s ? t = i && i.normalize ? i.normalize(t, (n = e, function (t) {
                return c(t, n)
            })) : c(t, e) : (s = (o = d(t = c(t, e)))[0], t = o[1], s && (i = _(s))), {
                f: s ? s + "!" + t : t,
                n: t,
                pr: s,
                p: i
            }
        }, f = {
            require: function (t) {
                return x(t)
            }, exports: function (t) {
                var e = m[t];
                return void 0 !== e ? e : m[t] = {}
            }, module: function (t) {
                return {
                    id: t, uri: "", exports: m[t], config: (e = t, function () {
                        return v && v.config && v.config[e] || {}
                    })
                };
                var e
            }
        }, s = function (t, e, i, n) {
            var o, s, a, r, l, c = [], d = typeof i, u = C(n = n || t);
            if ("undefined" == d || "function" == d) {
                for (e = !e.length && i.length ? ["require", "exports", "module"] : e, r = 0; r < e.length; r += 1) if ("require" === (s = (a = p(e[r], u)).f)) c[r] = f.require(t); else if ("exports" === s) c[r] = f.exports(t), l = !0; else if ("module" === s) o = c[r] = f.module(t); else if (w(m, s) || w(g, s) || w(b, s)) c[r] = _(s); else {
                    if (!a.p) throw new Error(t + " missing " + s);
                    a.p.load(a.n, x(n, !0), function (e) {
                        return function (t) {
                            m[e] = t
                        }
                    }(s), {}), c[r] = m[s]
                }
                d = i ? i.apply(m[t], c) : void 0, t && (o && o.exports !== h && o.exports !== m[t] ? m[t] = o.exports : d === h && l || (m[t] = d))
            } else t && (m[t] = i)
        }, t = i = a = function (t, e, i, n, o) {
            if ("string" == typeof t) return f[t] ? f[t](e) : _(p(t, C(e)).f);
            if (!t.splice) {
                if ((v = t).deps && a(v.deps, v.callback), !e) return;
                e.splice ? (t = e, e = i, i = null) : t = h
            }
            return e = e || function () {
            }, "function" == typeof i && (i = n, n = o), n ? s(h, t, e, i) : setTimeout(function () {
                s(h, t, e, i)
            }, 4), a
        }, a.config = function (t) {
            return a(t)
        }, t._defined = m, (r = function (t, e, i) {
            if ("string" != typeof t) throw new Error("See almond README: incorrect module build, no module name");
            e.splice || (i = e, e = []), w(m, t) || w(g, t) || (g[t] = [t, e, i])
        }).amd = {jQuery: !0}, l.requirejs = t, l.require = i, l.define = r), l.define("almond", function () {
        }), l.define("jquery", [], function () {
            var t = e || $;
            return null == t && console && console.error && console.error("Select2: An instance of jQuery or a jQuery-compatible library was not found. Make sure that you are including jQuery before Select2 on your web page."), t
        }), l.define("select2/utils", ["jquery"], function (s) {
            var n = {};

            function c(t) {
                var e, i = t.prototype, n = [];
                for (e in i) "function" == typeof i[e] && "constructor" !== e && n.push(e);
                return n
            }

            function t() {
                this.listeners = {}
            }

            n.Extend = function (t, e) {
                var i, n = {}.hasOwnProperty;

                function o() {
                    this.constructor = t
                }

                for (i in e) n.call(e, i) && (t[i] = e[i]);
                return o.prototype = e.prototype, t.prototype = new o, t.__super__ = e.prototype, t
            }, n.Decorate = function (n, o) {
                var t = c(o), e = c(n);

                function s() {
                    var t = Array.prototype.unshift, e = o.prototype.constructor.length, i = n.prototype.constructor;
                    0 < e && (t.call(arguments, n.prototype.constructor), i = o.prototype.constructor), i.apply(this, arguments)
                }

                o.displayName = n.displayName, s.prototype = new function () {
                    this.constructor = s
                };
                for (var i = 0; i < e.length; i++) {
                    var a = e[i];
                    s.prototype[a] = n.prototype[a]
                }
                for (var r = 0; r < t.length; r++) {
                    var l = t[r];
                    s.prototype[l] = function (t) {
                        var e = function () {
                        };
                        t in s.prototype && (e = s.prototype[t]);
                        var i = o.prototype[t];
                        return function () {
                            return Array.prototype.unshift.call(arguments, e), i.apply(this, arguments)
                        }
                    }(l)
                }
                return s
            }, t.prototype.on = function (t, e) {
                this.listeners = this.listeners || {}, t in this.listeners ? this.listeners[t].push(e) : this.listeners[t] = [e]
            }, t.prototype.trigger = function (t) {
                var e = Array.prototype.slice, i = e.call(arguments, 1);
                this.listeners = this.listeners || {}, 0 === (i = null == i ? [] : i).length && i.push({}), (i[0]._type = t) in this.listeners && this.invoke(this.listeners[t], e.call(arguments, 1)), "*" in this.listeners && this.invoke(this.listeners["*"], arguments)
            }, t.prototype.invoke = function (t, e) {
                for (var i = 0, n = t.length; i < n; i++) t[i].apply(this, e)
            }, n.Observable = t, n.generateChars = function (t) {
                for (var e = "", i = 0; i < t; i++) e += Math.floor(36 * Math.random()).toString(36);
                return e
            }, n.bind = function (t, e) {
                return function () {
                    t.apply(e, arguments)
                }
            }, n._convertData = function (t) {
                for (var e in t) {
                    var i = e.split("-"), n = t;
                    if (1 !== i.length) {
                        for (var o = 0; o < i.length; o++) {
                            var s = i[o];
                            (s = s.substring(0, 1).toLowerCase() + s.substring(1)) in n || (n[s] = {}), o == i.length - 1 && (n[s] = t[e]), n = n[s]
                        }
                        delete t[e]
                    }
                }
                return t
            }, n.hasScroll = function (t, e) {
                var i = s(e), n = e.style.overflowX, o = e.style.overflowY;
                return (n !== o || "hidden" !== o && "visible" !== o) && ("scroll" === n || "scroll" === o || i.innerHeight() < e.scrollHeight || i.innerWidth() < e.scrollWidth)
            }, n.escapeMarkup = function (t) {
                var e = {
                    "\\": "&#92;",
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                    "/": "&#47;"
                };
                return "string" != typeof t ? t : String(t).replace(/[&<>"'\/\\]/g, function (t) {
                    return e[t]
                })
            }, n.appendMany = function (t, e) {
                var i;
                "1.7" === s.fn.jquery.substr(0, 3) && (i = s(), s.map(e, function (t) {
                    i = i.add(t)
                }), e = i), t.append(e)
            }, n.__cache = {};
            var i = 0;
            return n.GetUniqueElementId = function (t) {
                var e = t.getAttribute("data-select2-id");
                return null == e && (t.id ? (e = t.id, t.setAttribute("data-select2-id", e)) : (t.setAttribute("data-select2-id", ++i), e = i.toString())), e
            }, n.StoreData = function (t, e, i) {
                t = n.GetUniqueElementId(t);
                n.__cache[t] || (n.__cache[t] = {}), n.__cache[t][e] = i
            }, n.GetData = function (t, e) {
                var i = n.GetUniqueElementId(t);
                return e ? n.__cache[i] && null != n.__cache[i][e] ? n.__cache[i][e] : s(t).data(e) : n.__cache[i]
            }, n.RemoveData = function (t) {
                var e = n.GetUniqueElementId(t);
                null != n.__cache[e] && delete n.__cache[e], t.removeAttribute("data-select2-id")
            }, n
        }), l.define("select2/results", ["jquery", "./utils"], function (u, h) {
            function n(t, e, i) {
                this.$element = t, this.data = i, this.options = e, n.__super__.constructor.call(this)
            }

            return h.Extend(n, h.Observable), n.prototype.render = function () {
                var t = u('<ul class="select2-results__options" role="listbox"></ul>');
                return this.options.get("multiple") && t.attr("aria-multiselectable", "true"), this.$results = t
            }, n.prototype.clear = function () {
                this.$results.empty()
            }, n.prototype.displayMessage = function (t) {
                var e = this.options.get("escapeMarkup");
                this.clear(), this.hideLoading();
                var i = u('<li role="alert" aria-live="assertive" class="select2-results__option"></li>'),
                    n = this.options.get("translations").get(t.message);
                i.append(e(n(t.args))), i[0].className += " select2-results__message", this.$results.append(i)
            }, n.prototype.hideMessages = function () {
                this.$results.find(".select2-results__message").remove()
            }, n.prototype.append = function (t) {
                this.hideLoading();
                var e = [];
                if (null != t.results && 0 !== t.results.length) {
                    t.results = this.sort(t.results);
                    for (var i = 0; i < t.results.length; i++) {
                        var n = t.results[i], n = this.option(n);
                        e.push(n)
                    }
                    this.$results.append(e)
                } else 0 === this.$results.children().length && this.trigger("results:message", {message: "noResults"})
            }, n.prototype.position = function (t, e) {
                e.find(".select2-results").append(t)
            }, n.prototype.sort = function (t) {
                return this.options.get("sorter")(t)
            }, n.prototype.highlightFirstItem = function () {
                var t = this.$results.find(".select2-results__option[aria-selected]"),
                    e = t.filter("[aria-selected=true]");
                (0 < e.length ? e : t).first().trigger("mouseenter"), this.ensureHighlightVisible()
            }, n.prototype.setClasses = function () {
                var e = this;
                this.data.current(function (t) {
                    var n = u.map(t, function (t) {
                        return t.id.toString()
                    });
                    e.$results.find(".select2-results__option[aria-selected]").each(function () {
                        var t = u(this), e = h.GetData(this, "data"), i = "" + e.id;
                        null != e.element && e.element.selected || null == e.element && -1 < u.inArray(i, n) ? t.attr("aria-selected", "true") : t.attr("aria-selected", "false")
                    })
                })
            }, n.prototype.showLoading = function (t) {
                this.hideLoading();
                t = {
                    disabled: !0,
                    loading: !0,
                    text: this.options.get("translations").get("searching")(t)
                }, t = this.option(t);
                t.className += " loading-results", this.$results.prepend(t)
            }, n.prototype.hideLoading = function () {
                this.$results.find(".loading-results").remove()
            }, n.prototype.option = function (t) {
                var e = document.createElement("li");
                e.className = "select2-results__option";
                var i, n = {role: "option", "aria-selected": "false"},
                    o = window.Element.prototype.matches || window.Element.prototype.msMatchesSelector || window.Element.prototype.webkitMatchesSelector;
                for (i in (null != t.element && o.call(t.element, ":disabled") || null == t.element && t.disabled) && (delete n["aria-selected"], n["aria-disabled"] = "true"), null == t.id && delete n["aria-selected"], null != t._resultId && (e.id = t._resultId), t.title && (e.title = t.title), t.children && (n.role = "group", n["aria-label"] = t.text, delete n["aria-selected"]), n) {
                    var s = n[i];
                    e.setAttribute(i, s)
                }
                if (t.children) {
                    var a = u(e), r = document.createElement("strong");
                    r.className = "select2-results__group", u(r), this.template(t, r);
                    for (var l = [], c = 0; c < t.children.length; c++) {
                        var d = t.children[c], d = this.option(d);
                        l.push(d)
                    }
                    o = u("<ul></ul>", {class: "select2-results__options select2-results__options--nested"});
                    o.append(l), a.append(r), a.append(o)
                } else this.template(t, e);
                return h.StoreData(e, "data", t), e
            }, n.prototype.bind = function (e, t) {
                var o = this, i = e.id + "-results";
                this.$results.attr("id", i), e.on("results:all", function (t) {
                    o.clear(), o.append(t.data), e.isOpen() && (o.setClasses(), o.highlightFirstItem())
                }), e.on("results:append", function (t) {
                    o.append(t.data), e.isOpen() && o.setClasses()
                }), e.on("query", function (t) {
                    o.hideMessages(), o.showLoading(t)
                }), e.on("select", function () {
                    e.isOpen() && (o.setClasses(), o.options.get("scrollAfterSelect") && o.highlightFirstItem())
                }), e.on("unselect", function () {
                    e.isOpen() && (o.setClasses(), o.options.get("scrollAfterSelect") && o.highlightFirstItem())
                }), e.on("open", function () {
                    o.$results.attr("aria-expanded", "true"), o.$results.attr("aria-hidden", "false"), o.setClasses(), o.ensureHighlightVisible()
                }), e.on("close", function () {
                    o.$results.attr("aria-expanded", "false"), o.$results.attr("aria-hidden", "true"), o.$results.removeAttr("aria-activedescendant")
                }), e.on("results:toggle", function () {
                    var t = o.getHighlightedResults();
                    0 !== t.length && t.trigger("mouseup")
                }), e.on("results:select", function () {
                    var t, e = o.getHighlightedResults();
                    0 !== e.length && (t = h.GetData(e[0], "data"), "true" == e.attr("aria-selected") ? o.trigger("close", {}) : o.trigger("select", {data: t}))
                }), e.on("results:previous", function () {
                    var t, e = o.getHighlightedResults(), i = o.$results.find("[aria-selected]"), n = i.index(e);
                    n <= 0 || (t = n - 1, 0 === e.length && (t = 0), (n = i.eq(t)).trigger("mouseenter"), e = o.$results.offset().top, i = n.offset().top, n = o.$results.scrollTop() + (i - e), 0 === t ? o.$results.scrollTop(0) : i - e < 0 && o.$results.scrollTop(n))
                }), e.on("results:next", function () {
                    var t, e = o.getHighlightedResults(), i = o.$results.find("[aria-selected]"), n = i.index(e) + 1;
                    n >= i.length || ((t = i.eq(n)).trigger("mouseenter"), e = o.$results.offset().top + o.$results.outerHeight(!1), i = t.offset().top + t.outerHeight(!1), t = o.$results.scrollTop() + i - e, 0 === n ? o.$results.scrollTop(0) : e < i && o.$results.scrollTop(t))
                }), e.on("results:focus", function (t) {
                    t.element.addClass("select2-results__option--highlighted")
                }), e.on("results:message", function (t) {
                    o.displayMessage(t)
                }), u.fn.mousewheel && this.$results.on("mousewheel", function (t) {
                    var e = o.$results.scrollTop(), i = o.$results.get(0).scrollHeight - e + t.deltaY,
                        e = 0 < t.deltaY && e - t.deltaY <= 0, i = t.deltaY < 0 && i <= o.$results.height();
                    e ? (o.$results.scrollTop(0), t.preventDefault(), t.stopPropagation()) : i && (o.$results.scrollTop(o.$results.get(0).scrollHeight - o.$results.height()), t.preventDefault(), t.stopPropagation())
                }), this.$results.on("mouseup", ".select2-results__option[aria-selected]", function (t) {
                    var e = u(this), i = h.GetData(this, "data");
                    "true" !== e.attr("aria-selected") ? o.trigger("select", {
                        originalEvent: t,
                        data: i
                    }) : o.options.get("multiple") ? o.trigger("unselect", {
                        originalEvent: t,
                        data: i
                    }) : o.trigger("close", {})
                }), this.$results.on("mouseenter", ".select2-results__option[aria-selected]", function (t) {
                    var e = h.GetData(this, "data");
                    o.getHighlightedResults().removeClass("select2-results__option--highlighted"), o.trigger("results:focus", {
                        data: e,
                        element: u(this)
                    })
                })
            }, n.prototype.getHighlightedResults = function () {
                return this.$results.find(".select2-results__option--highlighted")
            }, n.prototype.destroy = function () {
                this.$results.remove()
            }, n.prototype.ensureHighlightVisible = function () {
                var t, e, i, n, o = this.getHighlightedResults();
                0 !== o.length && (t = this.$results.find("[aria-selected]").index(o), n = this.$results.offset().top, e = o.offset().top, i = this.$results.scrollTop() + (e - n), n = e - n, i -= 2 * o.outerHeight(!1), t <= 2 ? this.$results.scrollTop(0) : (n > this.$results.outerHeight() || n < 0) && this.$results.scrollTop(i))
            }, n.prototype.template = function (t, e) {
                var i = this.options.get("templateResult"), n = this.options.get("escapeMarkup"), t = i(t, e);
                null == t ? e.style.display = "none" : "string" == typeof t ? e.innerHTML = n(t) : u(e).append(t)
            }, n
        }), l.define("select2/keys", [], function () {
            return {
                BACKSPACE: 8,
                TAB: 9,
                ENTER: 13,
                SHIFT: 16,
                CTRL: 17,
                ALT: 18,
                ESC: 27,
                SPACE: 32,
                PAGE_UP: 33,
                PAGE_DOWN: 34,
                END: 35,
                HOME: 36,
                LEFT: 37,
                UP: 38,
                RIGHT: 39,
                DOWN: 40,
                DELETE: 46
            }
        }), l.define("select2/selection/base", ["jquery", "../utils", "../keys"], function (i, n, o) {
            function s(t, e) {
                this.$element = t, this.options = e, s.__super__.constructor.call(this)
            }

            return n.Extend(s, n.Observable), s.prototype.render = function () {
                var t = i('<span class="select2-selection" role="combobox"  aria-haspopup="true" aria-expanded="false"></span>');
                return this._tabindex = 0, null != n.GetData(this.$element[0], "old-tabindex") ? this._tabindex = n.GetData(this.$element[0], "old-tabindex") : null != this.$element.attr("tabindex") && (this._tabindex = this.$element.attr("tabindex")), t.attr("title", this.$element.attr("title")), t.attr("tabindex", this._tabindex), t.attr("aria-disabled", "false"), this.$selection = t
            }, s.prototype.bind = function (t, e) {
                var i = this, n = t.id + "-results";
                this.container = t, this.$selection.on("focus", function (t) {
                    i.trigger("focus", t)
                }), this.$selection.on("blur", function (t) {
                    i._handleBlur(t)
                }), this.$selection.on("keydown", function (t) {
                    i.trigger("keypress", t), t.which === o.SPACE && t.preventDefault()
                }), t.on("results:focus", function (t) {
                    i.$selection.attr("aria-activedescendant", t.data._resultId)
                }), t.on("selection:update", function (t) {
                    i.update(t.data)
                }), t.on("open", function () {
                    i.$selection.attr("aria-expanded", "true"), i.$selection.attr("aria-owns", n), i._attachCloseHandler(t)
                }), t.on("close", function () {
                    i.$selection.attr("aria-expanded", "false"), i.$selection.removeAttr("aria-activedescendant"), i.$selection.removeAttr("aria-owns"), i.$selection.trigger("focus"), i._detachCloseHandler(t)
                }), t.on("enable", function () {
                    i.$selection.attr("tabindex", i._tabindex), i.$selection.attr("aria-disabled", "false")
                }), t.on("disable", function () {
                    i.$selection.attr("tabindex", "-1"), i.$selection.attr("aria-disabled", "true")
                })
            }, s.prototype._handleBlur = function (t) {
                var e = this;
                window.setTimeout(function () {
                    document.activeElement == e.$selection[0] || i.contains(e.$selection[0], document.activeElement) || e.trigger("blur", t)
                }, 1)
            }, s.prototype._attachCloseHandler = function (t) {
                i(document.body).on("mousedown.select2." + t.id, function (t) {
                    var e = i(t.target).closest(".select2");
                    i(".select2.select2-container--open").each(function () {
                        this != e[0] && n.GetData(this, "element").select2("close")
                    })
                })
            }, s.prototype._detachCloseHandler = function (t) {
                i(document.body).off("mousedown.select2." + t.id)
            }, s.prototype.position = function (t, e) {
                e.find(".selection").append(t)
            }, s.prototype.destroy = function () {
                this._detachCloseHandler(this.container)
            }, s.prototype.update = function (t) {
                throw new Error("The `update` method must be defined in child classes.")
            }, s
        }), l.define("select2/selection/single", ["jquery", "./base", "../utils", "../keys"], function (t, e, i, n) {
            function o() {
                o.__super__.constructor.apply(this, arguments)
            }

            return i.Extend(o, e), o.prototype.render = function () {
                var t = o.__super__.render.call(this);
                return t.addClass("select2-selection--single"), t.html('<span class="select2-selection__rendered"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>'), t
            }, o.prototype.bind = function (e, t) {
                var i = this;
                o.__super__.bind.apply(this, arguments);
                var n = e.id + "-container";
                this.$selection.find(".select2-selection__rendered").attr("id", n).attr("role", "textbox").attr("aria-readonly", "true"), this.$selection.attr("aria-labelledby", n), this.$selection.on("mousedown", function (t) {
                    1 === t.which && i.trigger("toggle", {originalEvent: t})
                }), this.$selection.on("focus", function (t) {
                }), this.$selection.on("blur", function (t) {
                }), e.on("focus", function (t) {
                    e.isOpen() || i.$selection.trigger("focus")
                })
            }, o.prototype.clear = function () {
                var t = this.$selection.find(".select2-selection__rendered");
                t.empty(), t.removeAttr("title")
            }, o.prototype.display = function (t, e) {
                var i = this.options.get("templateSelection");
                return this.options.get("escapeMarkup")(i(t, e))
            }, o.prototype.selectionContainer = function () {
                return t("<span></span>")
            }, o.prototype.update = function (t) {
                var e, i;
                0 !== t.length ? (i = t[0], e = this.$selection.find(".select2-selection__rendered"), t = this.display(i, e), e.empty().append(t), (i = i.title || i.text) ? e.attr("title", i) : e.removeAttr("title")) : this.clear()
            }, o
        }), l.define("select2/selection/multiple", ["jquery", "./base", "../utils"], function (n, t, r) {
            function o(t, e) {
                o.__super__.constructor.apply(this, arguments)
            }

            return r.Extend(o, t), o.prototype.render = function () {
                var t = o.__super__.render.call(this);
                return t.addClass("select2-selection--multiple"), t.html('<ul class="select2-selection__rendered"></ul>'), t
            }, o.prototype.bind = function (t, e) {
                var i = this;
                o.__super__.bind.apply(this, arguments), this.$selection.on("click", function (t) {
                    i.trigger("toggle", {originalEvent: t})
                }), this.$selection.on("click", ".select2-selection__choice__remove", function (t) {
                    var e;
                    i.options.get("disabled") || (e = n(this).parent(), e = r.GetData(e[0], "data"), i.trigger("unselect", {
                        originalEvent: t,
                        data: e
                    }))
                })
            }, o.prototype.clear = function () {
                var t = this.$selection.find(".select2-selection__rendered");
                t.empty(), t.removeAttr("title")
            }, o.prototype.display = function (t, e) {
                var i = this.options.get("templateSelection");
                return this.options.get("escapeMarkup")(i(t, e))
            }, o.prototype.selectionContainer = function () {
                return n('<li class="select2-selection__choice"><span class="select2-selection__choice__remove" role="presentation">&times;</span></li>')
            }, o.prototype.update = function (t) {
                if (this.clear(), 0 !== t.length) {
                    for (var e = [], i = 0; i < t.length; i++) {
                        var n = t[i], o = this.selectionContainer(), s = this.display(n, o);
                        o.append(s);
                        s = n.title || n.text;
                        s && o.attr("title", s), r.StoreData(o[0], "data", n), e.push(o)
                    }
                    var a = this.$selection.find(".select2-selection__rendered");
                    r.appendMany(a, e)
                }
            }, o
        }), l.define("select2/selection/placeholder", ["../utils"], function (t) {
            function e(t, e, i) {
                this.placeholder = this.normalizePlaceholder(i.get("placeholder")), t.call(this, e, i)
            }

            return e.prototype.normalizePlaceholder = function (t, e) {
                return e = "string" == typeof e ? {id: "", text: e} : e
            }, e.prototype.createPlaceholder = function (t, e) {
                var i = this.selectionContainer();
                return i.html(this.display(e)), i.addClass("select2-selection__placeholder").removeClass("select2-selection__choice"), i
            }, e.prototype.update = function (t, e) {
                var i = 1 == e.length && e[0].id != this.placeholder.id;
                if (1 < e.length || i) return t.call(this, e);
                this.clear();
                e = this.createPlaceholder(this.placeholder);
                this.$selection.find(".select2-selection__rendered").append(e)
            }, e
        }), l.define("select2/selection/allowClear", ["jquery", "../keys", "../utils"], function (i, n, r) {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), null == this.placeholder && this.options.get("debug") && window.console && console.error && console.error("Select2: The `allowClear` option should be used in combination with the `placeholder` option."), this.$selection.on("mousedown", ".select2-selection__clear", function (t) {
                    n._handleClear(t)
                }), e.on("keypress", function (t) {
                    n._handleKeyboardClear(t, e)
                })
            }, t.prototype._handleClear = function (t, e) {
                if (!this.options.get("disabled")) {
                    var i = this.$selection.find(".select2-selection__clear");
                    if (0 !== i.length) {
                        e.stopPropagation();
                        var n = r.GetData(i[0], "data"), o = this.$element.val();
                        this.$element.val(this.placeholder.id);
                        var s = {data: n};
                        if (this.trigger("clear", s), s.prevented) this.$element.val(o); else {
                            for (var a = 0; a < n.length; a++) if (s = {data: n[a]}, this.trigger("unselect", s), s.prevented) return void this.$element.val(o);
                            this.$element.trigger("change"), this.trigger("toggle", {})
                        }
                    }
                }
            }, t.prototype._handleKeyboardClear = function (t, e, i) {
                i.isOpen() || e.which != n.DELETE && e.which != n.BACKSPACE || this._handleClear(e)
            }, t.prototype.update = function (t, e) {
                t.call(this, e), 0 < this.$selection.find(".select2-selection__placeholder").length || 0 === e.length || (t = this.options.get("translations").get("removeAllItems"), t = i('<span class="select2-selection__clear" title="' + t() + '">&times;</span>'), r.StoreData(t[0], "data", e), this.$selection.find(".select2-selection__rendered").prepend(t))
            }, t
        }), l.define("select2/selection/search", ["jquery", "../utils", "../keys"], function (i, a, r) {
            function t(t, e, i) {
                t.call(this, e, i)
            }

            return t.prototype.render = function (t) {
                var e = i('<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" /></li>');
                this.$searchContainer = e, this.$search = e.find("input");
                t = t.call(this);
                return this._transferTabIndex(), t
            }, t.prototype.bind = function (t, e, i) {
                var n = this, o = e.id + "-results";
                t.call(this, e, i), e.on("open", function () {
                    n.$search.attr("aria-controls", o), n.$search.trigger("focus")
                }), e.on("close", function () {
                    n.$search.val(""), n.$search.removeAttr("aria-controls"), n.$search.removeAttr("aria-activedescendant"), n.$search.trigger("focus")
                }), e.on("enable", function () {
                    n.$search.prop("disabled", !1), n._transferTabIndex()
                }), e.on("disable", function () {
                    n.$search.prop("disabled", !0)
                }), e.on("focus", function (t) {
                    n.$search.trigger("focus")
                }), e.on("results:focus", function (t) {
                    t.data._resultId ? n.$search.attr("aria-activedescendant", t.data._resultId) : n.$search.removeAttr("aria-activedescendant")
                }), this.$selection.on("focusin", ".select2-search--inline", function (t) {
                    n.trigger("focus", t)
                }), this.$selection.on("focusout", ".select2-search--inline", function (t) {
                    n._handleBlur(t)
                }), this.$selection.on("keydown", ".select2-search--inline", function (t) {
                    var e;
                    t.stopPropagation(), n.trigger("keypress", t), n._keyUpPrevented = t.isDefaultPrevented(), t.which === r.BACKSPACE && "" === n.$search.val() && 0 < (e = n.$searchContainer.prev(".select2-selection__choice")).length && (e = a.GetData(e[0], "data"), n.searchRemoveChoice(e), t.preventDefault())
                }), this.$selection.on("click", ".select2-search--inline", function (t) {
                    n.$search.val() && t.stopPropagation()
                });
                var e = document.documentMode, s = e && e <= 11;
                this.$selection.on("input.searchcheck", ".select2-search--inline", function (t) {
                    s ? n.$selection.off("input.search input.searchcheck") : n.$selection.off("keyup.search")
                }), this.$selection.on("keyup.search input.search", ".select2-search--inline", function (t) {
                    var e;
                    s && "input" === t.type ? n.$selection.off("input.search input.searchcheck") : (e = t.which) != r.SHIFT && e != r.CTRL && e != r.ALT && e != r.TAB && n.handleSearch(t)
                })
            }, t.prototype._transferTabIndex = function (t) {
                this.$search.attr("tabindex", this.$selection.attr("tabindex")), this.$selection.attr("tabindex", "-1")
            }, t.prototype.createPlaceholder = function (t, e) {
                this.$search.attr("placeholder", e.text)
            }, t.prototype.update = function (t, e) {
                var i = this.$search[0] == document.activeElement;
                this.$search.attr("placeholder", ""), t.call(this, e), this.$selection.find(".select2-selection__rendered").append(this.$searchContainer), this.resizeSearch(), i && this.$search.trigger("focus")
            }, t.prototype.handleSearch = function () {
                var t;
                this.resizeSearch(), this._keyUpPrevented || (t = this.$search.val(), this.trigger("query", {term: t})), this._keyUpPrevented = !1
            }, t.prototype.searchRemoveChoice = function (t, e) {
                this.trigger("unselect", {data: e}), this.$search.val(e.text), this.handleSearch()
            }, t.prototype.resizeSearch = function () {
                this.$search.css("width", "25px");
                var t = "",
                    t = "" !== this.$search.attr("placeholder") ? this.$selection.find(".select2-selection__rendered").width() : .75 * (this.$search.val().length + 1) + "em";
                this.$search.css("width", t)
            }, t
        }), l.define("select2/selection/eventRelay", ["jquery"], function (a) {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this,
                    o = ["open", "opening", "close", "closing", "select", "selecting", "unselect", "unselecting", "clear", "clearing"],
                    s = ["opening", "closing", "selecting", "unselecting", "clearing"];
                t.call(this, e, i), e.on("*", function (t, e) {
                    var i;
                    -1 !== a.inArray(t, o) && (i = a.Event("select2:" + t, {params: e = e || {}}), n.$element.trigger(i), -1 !== a.inArray(t, s) && (e.prevented = i.isDefaultPrevented()))
                })
            }, t
        }), l.define("select2/translation", ["jquery", "require"], function (e, i) {
            function n(t) {
                this.dict = t || {}
            }

            return n.prototype.all = function () {
                return this.dict
            }, n.prototype.get = function (t) {
                return this.dict[t]
            }, n.prototype.extend = function (t) {
                this.dict = e.extend({}, t.all(), this.dict)
            }, n._cache = {}, n.loadPath = function (t) {
                var e;
                return t in n._cache || (e = i(t), n._cache[t] = e), new n(n._cache[t])
            }, n
        }), l.define("select2/diacritics", [], function () {
            return {
                "Ⓐ": "A",
                "Ａ": "A",
                "À": "A",
                "Á": "A",
                "Â": "A",
                "Ầ": "A",
                "Ấ": "A",
                "Ẫ": "A",
                "Ẩ": "A",
                "Ã": "A",
                "Ā": "A",
                "Ă": "A",
                "Ằ": "A",
                "Ắ": "A",
                "Ẵ": "A",
                "Ẳ": "A",
                "Ȧ": "A",
                "Ǡ": "A",
                "Ä": "A",
                "Ǟ": "A",
                "Ả": "A",
                "Å": "A",
                "Ǻ": "A",
                "Ǎ": "A",
                "Ȁ": "A",
                "Ȃ": "A",
                "Ạ": "A",
                "Ậ": "A",
                "Ặ": "A",
                "Ḁ": "A",
                "Ą": "A",
                "Ⱥ": "A",
                "Ɐ": "A",
                "Ꜳ": "AA",
                "Æ": "AE",
                "Ǽ": "AE",
                "Ǣ": "AE",
                "Ꜵ": "AO",
                "Ꜷ": "AU",
                "Ꜹ": "AV",
                "Ꜻ": "AV",
                "Ꜽ": "AY",
                "Ⓑ": "B",
                "Ｂ": "B",
                "Ḃ": "B",
                "Ḅ": "B",
                "Ḇ": "B",
                "Ƀ": "B",
                "Ƃ": "B",
                "Ɓ": "B",
                "Ⓒ": "C",
                "Ｃ": "C",
                "Ć": "C",
                "Ĉ": "C",
                "Ċ": "C",
                "Č": "C",
                "Ç": "C",
                "Ḉ": "C",
                "Ƈ": "C",
                "Ȼ": "C",
                "Ꜿ": "C",
                "Ⓓ": "D",
                "Ｄ": "D",
                "Ḋ": "D",
                "Ď": "D",
                "Ḍ": "D",
                "Ḑ": "D",
                "Ḓ": "D",
                "Ḏ": "D",
                "Đ": "D",
                "Ƌ": "D",
                "Ɗ": "D",
                "Ɖ": "D",
                "Ꝺ": "D",
                "Ǳ": "DZ",
                "Ǆ": "DZ",
                "ǲ": "Dz",
                "ǅ": "Dz",
                "Ⓔ": "E",
                "Ｅ": "E",
                "È": "E",
                "É": "E",
                "Ê": "E",
                "Ề": "E",
                "Ế": "E",
                "Ễ": "E",
                "Ể": "E",
                "Ẽ": "E",
                "Ē": "E",
                "Ḕ": "E",
                "Ḗ": "E",
                "Ĕ": "E",
                "Ė": "E",
                "Ë": "E",
                "Ẻ": "E",
                "Ě": "E",
                "Ȅ": "E",
                "Ȇ": "E",
                "Ẹ": "E",
                "Ệ": "E",
                "Ȩ": "E",
                "Ḝ": "E",
                "Ę": "E",
                "Ḙ": "E",
                "Ḛ": "E",
                "Ɛ": "E",
                "Ǝ": "E",
                "Ⓕ": "F",
                "Ｆ": "F",
                "Ḟ": "F",
                "Ƒ": "F",
                "Ꝼ": "F",
                "Ⓖ": "G",
                "Ｇ": "G",
                "Ǵ": "G",
                "Ĝ": "G",
                "Ḡ": "G",
                "Ğ": "G",
                "Ġ": "G",
                "Ǧ": "G",
                "Ģ": "G",
                "Ǥ": "G",
                "Ɠ": "G",
                "Ꞡ": "G",
                "Ᵹ": "G",
                "Ꝿ": "G",
                "Ⓗ": "H",
                "Ｈ": "H",
                "Ĥ": "H",
                "Ḣ": "H",
                "Ḧ": "H",
                "Ȟ": "H",
                "Ḥ": "H",
                "Ḩ": "H",
                "Ḫ": "H",
                "Ħ": "H",
                "Ⱨ": "H",
                "Ⱶ": "H",
                "Ɥ": "H",
                "Ⓘ": "I",
                "Ｉ": "I",
                "Ì": "I",
                "Í": "I",
                "Î": "I",
                "Ĩ": "I",
                "Ī": "I",
                "Ĭ": "I",
                "İ": "I",
                "Ï": "I",
                "Ḯ": "I",
                "Ỉ": "I",
                "Ǐ": "I",
                "Ȉ": "I",
                "Ȋ": "I",
                "Ị": "I",
                "Į": "I",
                "Ḭ": "I",
                "Ɨ": "I",
                "Ⓙ": "J",
                "Ｊ": "J",
                "Ĵ": "J",
                "Ɉ": "J",
                "Ⓚ": "K",
                "Ｋ": "K",
                "Ḱ": "K",
                "Ǩ": "K",
                "Ḳ": "K",
                "Ķ": "K",
                "Ḵ": "K",
                "Ƙ": "K",
                "Ⱪ": "K",
                "Ꝁ": "K",
                "Ꝃ": "K",
                "Ꝅ": "K",
                "Ꞣ": "K",
                "Ⓛ": "L",
                "Ｌ": "L",
                "Ŀ": "L",
                "Ĺ": "L",
                "Ľ": "L",
                "Ḷ": "L",
                "Ḹ": "L",
                "Ļ": "L",
                "Ḽ": "L",
                "Ḻ": "L",
                "Ł": "L",
                "Ƚ": "L",
                "Ɫ": "L",
                "Ⱡ": "L",
                "Ꝉ": "L",
                "Ꝇ": "L",
                "Ꞁ": "L",
                "Ǉ": "LJ",
                "ǈ": "Lj",
                "Ⓜ": "M",
                "Ｍ": "M",
                "Ḿ": "M",
                "Ṁ": "M",
                "Ṃ": "M",
                "Ɱ": "M",
                "Ɯ": "M",
                "Ⓝ": "N",
                "Ｎ": "N",
                "Ǹ": "N",
                "Ń": "N",
                "Ñ": "N",
                "Ṅ": "N",
                "Ň": "N",
                "Ṇ": "N",
                "Ņ": "N",
                "Ṋ": "N",
                "Ṉ": "N",
                "Ƞ": "N",
                "Ɲ": "N",
                "Ꞑ": "N",
                "Ꞥ": "N",
                "Ǌ": "NJ",
                "ǋ": "Nj",
                "Ⓞ": "O",
                "Ｏ": "O",
                "Ò": "O",
                "Ó": "O",
                "Ô": "O",
                "Ồ": "O",
                "Ố": "O",
                "Ỗ": "O",
                "Ổ": "O",
                "Õ": "O",
                "Ṍ": "O",
                "Ȭ": "O",
                "Ṏ": "O",
                "Ō": "O",
                "Ṑ": "O",
                "Ṓ": "O",
                "Ŏ": "O",
                "Ȯ": "O",
                "Ȱ": "O",
                "Ö": "O",
                "Ȫ": "O",
                "Ỏ": "O",
                "Ő": "O",
                "Ǒ": "O",
                "Ȍ": "O",
                "Ȏ": "O",
                "Ơ": "O",
                "Ờ": "O",
                "Ớ": "O",
                "Ỡ": "O",
                "Ở": "O",
                "Ợ": "O",
                "Ọ": "O",
                "Ộ": "O",
                "Ǫ": "O",
                "Ǭ": "O",
                "Ø": "O",
                "Ǿ": "O",
                "Ɔ": "O",
                "Ɵ": "O",
                "Ꝋ": "O",
                "Ꝍ": "O",
                "Œ": "OE",
                "Ƣ": "OI",
                "Ꝏ": "OO",
                "Ȣ": "OU",
                "Ⓟ": "P",
                "Ｐ": "P",
                "Ṕ": "P",
                "Ṗ": "P",
                "Ƥ": "P",
                "Ᵽ": "P",
                "Ꝑ": "P",
                "Ꝓ": "P",
                "Ꝕ": "P",
                "Ⓠ": "Q",
                "Ｑ": "Q",
                "Ꝗ": "Q",
                "Ꝙ": "Q",
                "Ɋ": "Q",
                "Ⓡ": "R",
                "Ｒ": "R",
                "Ŕ": "R",
                "Ṙ": "R",
                "Ř": "R",
                "Ȑ": "R",
                "Ȓ": "R",
                "Ṛ": "R",
                "Ṝ": "R",
                "Ŗ": "R",
                "Ṟ": "R",
                "Ɍ": "R",
                "Ɽ": "R",
                "Ꝛ": "R",
                "Ꞧ": "R",
                "Ꞃ": "R",
                "Ⓢ": "S",
                "Ｓ": "S",
                "ẞ": "S",
                "Ś": "S",
                "Ṥ": "S",
                "Ŝ": "S",
                "Ṡ": "S",
                "Š": "S",
                "Ṧ": "S",
                "Ṣ": "S",
                "Ṩ": "S",
                "Ș": "S",
                "Ş": "S",
                "Ȿ": "S",
                "Ꞩ": "S",
                "Ꞅ": "S",
                "Ⓣ": "T",
                "Ｔ": "T",
                "Ṫ": "T",
                "Ť": "T",
                "Ṭ": "T",
                "Ț": "T",
                "Ţ": "T",
                "Ṱ": "T",
                "Ṯ": "T",
                "Ŧ": "T",
                "Ƭ": "T",
                "Ʈ": "T",
                "Ⱦ": "T",
                "Ꞇ": "T",
                "Ꜩ": "TZ",
                "Ⓤ": "U",
                "Ｕ": "U",
                "Ù": "U",
                "Ú": "U",
                "Û": "U",
                "Ũ": "U",
                "Ṹ": "U",
                "Ū": "U",
                "Ṻ": "U",
                "Ŭ": "U",
                "Ü": "U",
                "Ǜ": "U",
                "Ǘ": "U",
                "Ǖ": "U",
                "Ǚ": "U",
                "Ủ": "U",
                "Ů": "U",
                "Ű": "U",
                "Ǔ": "U",
                "Ȕ": "U",
                "Ȗ": "U",
                "Ư": "U",
                "Ừ": "U",
                "Ứ": "U",
                "Ữ": "U",
                "Ử": "U",
                "Ự": "U",
                "Ụ": "U",
                "Ṳ": "U",
                "Ų": "U",
                "Ṷ": "U",
                "Ṵ": "U",
                "Ʉ": "U",
                "Ⓥ": "V",
                "Ｖ": "V",
                "Ṽ": "V",
                "Ṿ": "V",
                "Ʋ": "V",
                "Ꝟ": "V",
                "Ʌ": "V",
                "Ꝡ": "VY",
                "Ⓦ": "W",
                "Ｗ": "W",
                "Ẁ": "W",
                "Ẃ": "W",
                "Ŵ": "W",
                "Ẇ": "W",
                "Ẅ": "W",
                "Ẉ": "W",
                "Ⱳ": "W",
                "Ⓧ": "X",
                "Ｘ": "X",
                "Ẋ": "X",
                "Ẍ": "X",
                "Ⓨ": "Y",
                "Ｙ": "Y",
                "Ỳ": "Y",
                "Ý": "Y",
                "Ŷ": "Y",
                "Ỹ": "Y",
                "Ȳ": "Y",
                "Ẏ": "Y",
                "Ÿ": "Y",
                "Ỷ": "Y",
                "Ỵ": "Y",
                "Ƴ": "Y",
                "Ɏ": "Y",
                "Ỿ": "Y",
                "Ⓩ": "Z",
                "Ｚ": "Z",
                "Ź": "Z",
                "Ẑ": "Z",
                "Ż": "Z",
                "Ž": "Z",
                "Ẓ": "Z",
                "Ẕ": "Z",
                "Ƶ": "Z",
                "Ȥ": "Z",
                "Ɀ": "Z",
                "Ⱬ": "Z",
                "Ꝣ": "Z",
                "ⓐ": "a",
                "ａ": "a",
                "ẚ": "a",
                "à": "a",
                "á": "a",
                "â": "a",
                "ầ": "a",
                "ấ": "a",
                "ẫ": "a",
                "ẩ": "a",
                "ã": "a",
                "ā": "a",
                "ă": "a",
                "ằ": "a",
                "ắ": "a",
                "ẵ": "a",
                "ẳ": "a",
                "ȧ": "a",
                "ǡ": "a",
                "ä": "a",
                "ǟ": "a",
                "ả": "a",
                "å": "a",
                "ǻ": "a",
                "ǎ": "a",
                "ȁ": "a",
                "ȃ": "a",
                "ạ": "a",
                "ậ": "a",
                "ặ": "a",
                "ḁ": "a",
                "ą": "a",
                "ⱥ": "a",
                "ɐ": "a",
                "ꜳ": "aa",
                "æ": "ae",
                "ǽ": "ae",
                "ǣ": "ae",
                "ꜵ": "ao",
                "ꜷ": "au",
                "ꜹ": "av",
                "ꜻ": "av",
                "ꜽ": "ay",
                "ⓑ": "b",
                "ｂ": "b",
                "ḃ": "b",
                "ḅ": "b",
                "ḇ": "b",
                "ƀ": "b",
                "ƃ": "b",
                "ɓ": "b",
                "ⓒ": "c",
                "ｃ": "c",
                "ć": "c",
                "ĉ": "c",
                "ċ": "c",
                "č": "c",
                "ç": "c",
                "ḉ": "c",
                "ƈ": "c",
                "ȼ": "c",
                "ꜿ": "c",
                "ↄ": "c",
                "ⓓ": "d",
                "ｄ": "d",
                "ḋ": "d",
                "ď": "d",
                "ḍ": "d",
                "ḑ": "d",
                "ḓ": "d",
                "ḏ": "d",
                "đ": "d",
                "ƌ": "d",
                "ɖ": "d",
                "ɗ": "d",
                "ꝺ": "d",
                "ǳ": "dz",
                "ǆ": "dz",
                "ⓔ": "e",
                "ｅ": "e",
                "è": "e",
                "é": "e",
                "ê": "e",
                "ề": "e",
                "ế": "e",
                "ễ": "e",
                "ể": "e",
                "ẽ": "e",
                "ē": "e",
                "ḕ": "e",
                "ḗ": "e",
                "ĕ": "e",
                "ė": "e",
                "ë": "e",
                "ẻ": "e",
                "ě": "e",
                "ȅ": "e",
                "ȇ": "e",
                "ẹ": "e",
                "ệ": "e",
                "ȩ": "e",
                "ḝ": "e",
                "ę": "e",
                "ḙ": "e",
                "ḛ": "e",
                "ɇ": "e",
                "ɛ": "e",
                "ǝ": "e",
                "ⓕ": "f",
                "ｆ": "f",
                "ḟ": "f",
                "ƒ": "f",
                "ꝼ": "f",
                "ⓖ": "g",
                "ｇ": "g",
                "ǵ": "g",
                "ĝ": "g",
                "ḡ": "g",
                "ğ": "g",
                "ġ": "g",
                "ǧ": "g",
                "ģ": "g",
                "ǥ": "g",
                "ɠ": "g",
                "ꞡ": "g",
                "ᵹ": "g",
                "ꝿ": "g",
                "ⓗ": "h",
                "ｈ": "h",
                "ĥ": "h",
                "ḣ": "h",
                "ḧ": "h",
                "ȟ": "h",
                "ḥ": "h",
                "ḩ": "h",
                "ḫ": "h",
                "ẖ": "h",
                "ħ": "h",
                "ⱨ": "h",
                "ⱶ": "h",
                "ɥ": "h",
                "ƕ": "hv",
                "ⓘ": "i",
                "ｉ": "i",
                "ì": "i",
                "í": "i",
                "î": "i",
                "ĩ": "i",
                "ī": "i",
                "ĭ": "i",
                "ï": "i",
                "ḯ": "i",
                "ỉ": "i",
                "ǐ": "i",
                "ȉ": "i",
                "ȋ": "i",
                "ị": "i",
                "į": "i",
                "ḭ": "i",
                "ɨ": "i",
                "ı": "i",
                "ⓙ": "j",
                "ｊ": "j",
                "ĵ": "j",
                "ǰ": "j",
                "ɉ": "j",
                "ⓚ": "k",
                "ｋ": "k",
                "ḱ": "k",
                "ǩ": "k",
                "ḳ": "k",
                "ķ": "k",
                "ḵ": "k",
                "ƙ": "k",
                "ⱪ": "k",
                "ꝁ": "k",
                "ꝃ": "k",
                "ꝅ": "k",
                "ꞣ": "k",
                "ⓛ": "l",
                "ｌ": "l",
                "ŀ": "l",
                "ĺ": "l",
                "ľ": "l",
                "ḷ": "l",
                "ḹ": "l",
                "ļ": "l",
                "ḽ": "l",
                "ḻ": "l",
                "ſ": "l",
                "ł": "l",
                "ƚ": "l",
                "ɫ": "l",
                "ⱡ": "l",
                "ꝉ": "l",
                "ꞁ": "l",
                "ꝇ": "l",
                "ǉ": "lj",
                "ⓜ": "m",
                "ｍ": "m",
                "ḿ": "m",
                "ṁ": "m",
                "ṃ": "m",
                "ɱ": "m",
                "ɯ": "m",
                "ⓝ": "n",
                "ｎ": "n",
                "ǹ": "n",
                "ń": "n",
                "ñ": "n",
                "ṅ": "n",
                "ň": "n",
                "ṇ": "n",
                "ņ": "n",
                "ṋ": "n",
                "ṉ": "n",
                "ƞ": "n",
                "ɲ": "n",
                "ŉ": "n",
                "ꞑ": "n",
                "ꞥ": "n",
                "ǌ": "nj",
                "ⓞ": "o",
                "ｏ": "o",
                "ò": "o",
                "ó": "o",
                "ô": "o",
                "ồ": "o",
                "ố": "o",
                "ỗ": "o",
                "ổ": "o",
                "õ": "o",
                "ṍ": "o",
                "ȭ": "o",
                "ṏ": "o",
                "ō": "o",
                "ṑ": "o",
                "ṓ": "o",
                "ŏ": "o",
                "ȯ": "o",
                "ȱ": "o",
                "ö": "o",
                "ȫ": "o",
                "ỏ": "o",
                "ő": "o",
                "ǒ": "o",
                "ȍ": "o",
                "ȏ": "o",
                "ơ": "o",
                "ờ": "o",
                "ớ": "o",
                "ỡ": "o",
                "ở": "o",
                "ợ": "o",
                "ọ": "o",
                "ộ": "o",
                "ǫ": "o",
                "ǭ": "o",
                "ø": "o",
                "ǿ": "o",
                "ɔ": "o",
                "ꝋ": "o",
                "ꝍ": "o",
                "ɵ": "o",
                "œ": "oe",
                "ƣ": "oi",
                "ȣ": "ou",
                "ꝏ": "oo",
                "ⓟ": "p",
                "ｐ": "p",
                "ṕ": "p",
                "ṗ": "p",
                "ƥ": "p",
                "ᵽ": "p",
                "ꝑ": "p",
                "ꝓ": "p",
                "ꝕ": "p",
                "ⓠ": "q",
                "ｑ": "q",
                "ɋ": "q",
                "ꝗ": "q",
                "ꝙ": "q",
                "ⓡ": "r",
                "ｒ": "r",
                "ŕ": "r",
                "ṙ": "r",
                "ř": "r",
                "ȑ": "r",
                "ȓ": "r",
                "ṛ": "r",
                "ṝ": "r",
                "ŗ": "r",
                "ṟ": "r",
                "ɍ": "r",
                "ɽ": "r",
                "ꝛ": "r",
                "ꞧ": "r",
                "ꞃ": "r",
                "ⓢ": "s",
                "ｓ": "s",
                "ß": "s",
                "ś": "s",
                "ṥ": "s",
                "ŝ": "s",
                "ṡ": "s",
                "š": "s",
                "ṧ": "s",
                "ṣ": "s",
                "ṩ": "s",
                "ș": "s",
                "ş": "s",
                "ȿ": "s",
                "ꞩ": "s",
                "ꞅ": "s",
                "ẛ": "s",
                "ⓣ": "t",
                "ｔ": "t",
                "ṫ": "t",
                "ẗ": "t",
                "ť": "t",
                "ṭ": "t",
                "ț": "t",
                "ţ": "t",
                "ṱ": "t",
                "ṯ": "t",
                "ŧ": "t",
                "ƭ": "t",
                "ʈ": "t",
                "ⱦ": "t",
                "ꞇ": "t",
                "ꜩ": "tz",
                "ⓤ": "u",
                "ｕ": "u",
                "ù": "u",
                "ú": "u",
                "û": "u",
                "ũ": "u",
                "ṹ": "u",
                "ū": "u",
                "ṻ": "u",
                "ŭ": "u",
                "ü": "u",
                "ǜ": "u",
                "ǘ": "u",
                "ǖ": "u",
                "ǚ": "u",
                "ủ": "u",
                "ů": "u",
                "ű": "u",
                "ǔ": "u",
                "ȕ": "u",
                "ȗ": "u",
                "ư": "u",
                "ừ": "u",
                "ứ": "u",
                "ữ": "u",
                "ử": "u",
                "ự": "u",
                "ụ": "u",
                "ṳ": "u",
                "ų": "u",
                "ṷ": "u",
                "ṵ": "u",
                "ʉ": "u",
                "ⓥ": "v",
                "ｖ": "v",
                "ṽ": "v",
                "ṿ": "v",
                "ʋ": "v",
                "ꝟ": "v",
                "ʌ": "v",
                "ꝡ": "vy",
                "ⓦ": "w",
                "ｗ": "w",
                "ẁ": "w",
                "ẃ": "w",
                "ŵ": "w",
                "ẇ": "w",
                "ẅ": "w",
                "ẘ": "w",
                "ẉ": "w",
                "ⱳ": "w",
                "ⓧ": "x",
                "ｘ": "x",
                "ẋ": "x",
                "ẍ": "x",
                "ⓨ": "y",
                "ｙ": "y",
                "ỳ": "y",
                "ý": "y",
                "ŷ": "y",
                "ỹ": "y",
                "ȳ": "y",
                "ẏ": "y",
                "ÿ": "y",
                "ỷ": "y",
                "ẙ": "y",
                "ỵ": "y",
                "ƴ": "y",
                "ɏ": "y",
                "ỿ": "y",
                "ⓩ": "z",
                "ｚ": "z",
                "ź": "z",
                "ẑ": "z",
                "ż": "z",
                "ž": "z",
                "ẓ": "z",
                "ẕ": "z",
                "ƶ": "z",
                "ȥ": "z",
                "ɀ": "z",
                "ⱬ": "z",
                "ꝣ": "z",
                "Ά": "Α",
                "Έ": "Ε",
                "Ή": "Η",
                "Ί": "Ι",
                "Ϊ": "Ι",
                "Ό": "Ο",
                "Ύ": "Υ",
                "Ϋ": "Υ",
                "Ώ": "Ω",
                "ά": "α",
                "έ": "ε",
                "ή": "η",
                "ί": "ι",
                "ϊ": "ι",
                "ΐ": "ι",
                "ό": "ο",
                "ύ": "υ",
                "ϋ": "υ",
                "ΰ": "υ",
                "ώ": "ω",
                "ς": "σ",
                "’": "'"
            }
        }), l.define("select2/data/base", ["../utils"], function (i) {
            function n(t, e) {
                n.__super__.constructor.call(this)
            }

            return i.Extend(n, i.Observable), n.prototype.current = function (t) {
                throw new Error("The `current` method must be defined in child classes.")
            }, n.prototype.query = function (t, e) {
                throw new Error("The `query` method must be defined in child classes.")
            }, n.prototype.bind = function (t, e) {
            }, n.prototype.destroy = function () {
            }, n.prototype.generateResultId = function (t, e) {
                t = t.id + "-result-";
                return t += i.generateChars(4), null != e.id ? t += "-" + e.id.toString() : t += "-" + i.generateChars(4), t
            }, n
        }), l.define("select2/data/select", ["./base", "../utils", "jquery"], function (t, a, r) {
            function i(t, e) {
                this.$element = t, this.options = e, i.__super__.constructor.call(this)
            }

            return a.Extend(i, t), i.prototype.current = function (t) {
                var e = [], i = this;
                this.$element.find(":selected").each(function () {
                    var t = r(this), t = i.item(t);
                    e.push(t)
                }), t(e)
            }, i.prototype.select = function (o) {
                var t, s = this;
                if (o.selected = !0, r(o.element).is("option")) return o.element.selected = !0, void this.$element.trigger("change");
                this.$element.prop("multiple") ? this.current(function (t) {
                    var e = [];
                    (o = [o]).push.apply(o, t);
                    for (var i = 0; i < o.length; i++) {
                        var n = o[i].id;
                        -1 === r.inArray(n, e) && e.push(n)
                    }
                    s.$element.val(e), s.$element.trigger("change")
                }) : (t = o.id, this.$element.val(t), this.$element.trigger("change"))
            }, i.prototype.unselect = function (o) {
                var s = this;
                if (this.$element.prop("multiple")) {
                    if (o.selected = !1, r(o.element).is("option")) return o.element.selected = !1, void this.$element.trigger("change");
                    this.current(function (t) {
                        for (var e = [], i = 0; i < t.length; i++) {
                            var n = t[i].id;
                            n !== o.id && -1 === r.inArray(n, e) && e.push(n)
                        }
                        s.$element.val(e), s.$element.trigger("change")
                    })
                }
            }, i.prototype.bind = function (t, e) {
                var i = this;
                (this.container = t).on("select", function (t) {
                    i.select(t.data)
                }), t.on("unselect", function (t) {
                    i.unselect(t.data)
                })
            }, i.prototype.destroy = function () {
                this.$element.find("*").each(function () {
                    a.RemoveData(this)
                })
            }, i.prototype.query = function (e, t) {
                var i = [], n = this;
                this.$element.children().each(function () {
                    var t = r(this);
                    (t.is("option") || t.is("optgroup")) && (t = n.item(t), null !== (t = n.matches(e, t)) && i.push(t))
                }), t({results: i})
            }, i.prototype.addOptions = function (t) {
                a.appendMany(this.$element, t)
            }, i.prototype.option = function (t) {
                var e;
                t.children ? (e = document.createElement("optgroup")).label = t.text : void 0 !== (e = document.createElement("option")).textContent ? e.textContent = t.text : e.innerText = t.text, void 0 !== t.id && (e.value = t.id), t.disabled && (e.disabled = !0), t.selected && (e.selected = !0), t.title && (e.title = t.title);
                var i = r(e), t = this._normalizeItem(t);
                return t.element = e, a.StoreData(e, "data", t), i
            }, i.prototype.item = function (t) {
                var e = {};
                if (null != (e = a.GetData(t[0], "data"))) return e;
                if (t.is("option")) e = {
                    id: t.val(),
                    text: t.text(),
                    disabled: t.prop("disabled"),
                    selected: t.prop("selected"),
                    title: t.prop("title")
                }; else if (t.is("optgroup")) {
                    for (var e = {
                        text: t.prop("label"),
                        children: [],
                        title: t.prop("title")
                    }, i = t.children("option"), n = [], o = 0; o < i.length; o++) {
                        var s = r(i[o]), s = this.item(s);
                        n.push(s)
                    }
                    e.children = n
                }
                return (e = this._normalizeItem(e)).element = t[0], a.StoreData(t[0], "data", e), e
            }, i.prototype._normalizeItem = function (t) {
                return t !== Object(t) && (t = {
                    id: t,
                    text: t
                }), null != (t = r.extend({}, {text: ""}, t)).id && (t.id = t.id.toString()), null != t.text && (t.text = t.text.toString()), null == t._resultId && t.id && null != this.container && (t._resultId = this.generateResultId(this.container, t)), r.extend({}, {
                    selected: !1,
                    disabled: !1
                }, t)
            }, i.prototype.matches = function (t, e) {
                return this.options.get("matcher")(t, e)
            }, i
        }), l.define("select2/data/array", ["./select", "../utils", "jquery"], function (t, c, d) {
            function n(t, e) {
                this._dataToConvert = e.get("data") || [], n.__super__.constructor.call(this, t, e)
            }

            return c.Extend(n, t), n.prototype.bind = function (t, e) {
                n.__super__.bind.call(this, t, e), this.addOptions(this.convertToOptions(this._dataToConvert))
            }, n.prototype.select = function (i) {
                var t;
                0 === (t = this.$element.find("option").filter(function (t, e) {
                    return e.value == i.id.toString()
                })).length && (t = this.option(i), this.addOptions(t)), n.__super__.select.call(this, i)
            }, n.prototype.convertToOptions = function (t) {
                var e = this, i = this.$element.find("option"), n = i.map(function () {
                    return e.item(d(this)).id
                }).get(), o = [];
                for (var s = 0; s < t.length; s++) {
                    var a, r, l = this._normalizeItem(t[s]);
                    0 <= d.inArray(l.id, n) ? (a = i.filter(function (t) {
                        return function () {
                            return d(this).val() == t.id
                        }
                    }(l)), r = this.item(a), r = d.extend(!0, {}, l, r), r = this.option(r), a.replaceWith(r)) : (r = this.option(l), l.children && (l = this.convertToOptions(l.children), c.appendMany(r, l)), o.push(r))
                }
                return o
            }, n
        }), l.define("select2/data/ajax", ["./array", "../utils", "jquery"], function (t, e, s) {
            function i(t, e) {
                this.ajaxOptions = this._applyDefaults(e.get("ajax")), null != this.ajaxOptions.processResults && (this.processResults = this.ajaxOptions.processResults), i.__super__.constructor.call(this, t, e)
            }

            return e.Extend(i, t), i.prototype._applyDefaults = function (t) {
                return s.extend({}, {
                    data: function (t) {
                        return s.extend({}, t, {q: t.term})
                    }, transport: function (t, e, i) {
                        t = s.ajax(t);
                        return t.then(e), t.fail(i), t
                    }
                }, t, !0)
            }, i.prototype.processResults = function (t) {
                return t
            }, i.prototype.query = function (e, i) {
                var n = this;
                null != this._request && (s.isFunction(this._request.abort) && this._request.abort(), this._request = null);
                var o = s.extend({type: "GET"}, this.ajaxOptions);

                function t() {
                    var t = o.transport(o, function (t) {
                        t = n.processResults(t, e);
                        n.options.get("debug") && window.console && console.error && (t && t.results && s.isArray(t.results) || console.error("Select2: The AJAX results did not return an array in the `results` key of the response.")), i(t)
                    }, function () {
                        "status" in t && (0 === t.status || "0" === t.status) || n.trigger("results:message", {message: "errorLoading"})
                    });
                    n._request = t
                }

                "function" == typeof o.url && (o.url = o.url.call(this.$element, e)), "function" == typeof o.data && (o.data = o.data.call(this.$element, e)), this.ajaxOptions.delay && null != e.term ? (this._queryTimeout && window.clearTimeout(this._queryTimeout), this._queryTimeout = window.setTimeout(t, this.ajaxOptions.delay)) : t()
            }, i
        }), l.define("select2/data/tags", ["jquery"], function (r) {
            function t(t, e, i) {
                var n = i.get("tags"), o = i.get("createTag");
                void 0 !== o && (this.createTag = o);
                o = i.get("insertTag");
                if (void 0 !== o && (this.insertTag = o), t.call(this, e, i), r.isArray(n)) for (var s = 0; s < n.length; s++) {
                    var a = n[s], a = this._normalizeItem(a), a = this.option(a);
                    this.$element.append(a)
                }
            }

            return t.prototype.query = function (t, c, d) {
                var u = this;
                this._removeOldTags(), null != c.term && null == c.page ? t.call(this, c, function t(e, i) {
                    for (var n = e.results, o = 0; o < n.length; o++) {
                        var s = n[o], a = null != s.children && !t({results: s.children}, !0);
                        if ((s.text || "").toUpperCase() === (c.term || "").toUpperCase() || a) return !i && (e.data = n, void d(e))
                    }
                    if (i) return !0;
                    var r, l = u.createTag(c);
                    null != l && ((r = u.option(l)).attr("data-select2-tag", !0), u.addOptions([r]), u.insertTag(n, l)), e.results = n, d(e)
                }) : t.call(this, c, d)
            }, t.prototype.createTag = function (t, e) {
                e = r.trim(e.term);
                return "" === e ? null : {id: e, text: e}
            }, t.prototype.insertTag = function (t, e, i) {
                e.unshift(i)
            }, t.prototype._removeOldTags = function (t) {
                this.$element.find("option[data-select2-tag]").each(function () {
                    this.selected || r(this).remove()
                })
            }, t
        }), l.define("select2/data/tokenizer", ["jquery"], function (c) {
            function t(t, e, i) {
                var n = i.get("tokenizer");
                void 0 !== n && (this.tokenizer = n), t.call(this, e, i)
            }

            return t.prototype.bind = function (t, e, i) {
                t.call(this, e, i), this.$search = e.dropdown.$search || e.selection.$search || i.find(".select2-search__field")
            }, t.prototype.query = function (t, e, i) {
                var n = this;
                e.term = e.term || "";
                var o = this.tokenizer(e, this.options, function (t) {
                    var e = n._normalizeItem(t);
                    n.$element.find("option").filter(function () {
                        return c(this).val() === e.id
                    }).length || ((t = n.option(e)).attr("data-select2-tag", !0), n._removeOldTags(), n.addOptions([t])), n.trigger("select", {data: e})
                });
                o.term !== e.term && (this.$search.length && (this.$search.val(o.term), this.$search.trigger("focus")), e.term = o.term), t.call(this, e, i)
            }, t.prototype.tokenizer = function (t, e, i, n) {
                for (var o = i.get("tokenSeparators") || [], s = e.term, a = 0, r = this.createTag || function (t) {
                    return {id: t.term, text: t.term}
                }; a < s.length;) {
                    var l = s[a];
                    -1 !== c.inArray(l, o) ? (l = s.substr(0, a), null != (l = r(c.extend({}, e, {term: l}))) ? (n(l), s = s.substr(a + 1) || "", a = 0) : a++) : a++
                }
                return {term: s}
            }, t
        }), l.define("select2/data/minimumInputLength", [], function () {
            function t(t, e, i) {
                this.minimumInputLength = i.get("minimumInputLength"), t.call(this, e, i)
            }

            return t.prototype.query = function (t, e, i) {
                e.term = e.term || "", e.term.length < this.minimumInputLength ? this.trigger("results:message", {
                    message: "inputTooShort",
                    args: {minimum: this.minimumInputLength, input: e.term, params: e}
                }) : t.call(this, e, i)
            }, t
        }), l.define("select2/data/maximumInputLength", [], function () {
            function t(t, e, i) {
                this.maximumInputLength = i.get("maximumInputLength"), t.call(this, e, i)
            }

            return t.prototype.query = function (t, e, i) {
                e.term = e.term || "", 0 < this.maximumInputLength && e.term.length > this.maximumInputLength ? this.trigger("results:message", {
                    message: "inputTooLong",
                    args: {maximum: this.maximumInputLength, input: e.term, params: e}
                }) : t.call(this, e, i)
            }, t
        }), l.define("select2/data/maximumSelectionLength", [], function () {
            function t(t, e, i) {
                this.maximumSelectionLength = i.get("maximumSelectionLength"), t.call(this, e, i)
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), e.on("select", function () {
                    n._checkIfMaximumSelected()
                })
            }, t.prototype.query = function (t, e, i) {
                var n = this;
                this._checkIfMaximumSelected(function () {
                    t.call(n, e, i)
                })
            }, t.prototype._checkIfMaximumSelected = function (t, e) {
                var i = this;
                this.current(function (t) {
                    t = null != t ? t.length : 0;
                    0 < i.maximumSelectionLength && t >= i.maximumSelectionLength ? i.trigger("results:message", {
                        message: "maximumSelected",
                        args: {maximum: i.maximumSelectionLength}
                    }) : e && e()
                })
            }, t
        }), l.define("select2/dropdown", ["jquery", "./utils"], function (e, t) {
            function i(t, e) {
                this.$element = t, this.options = e, i.__super__.constructor.call(this)
            }

            return t.Extend(i, t.Observable), i.prototype.render = function () {
                var t = e('<span class="select2-dropdown"><span class="select2-results"></span></span>');
                return t.attr("dir", this.options.get("dir")), this.$dropdown = t
            }, i.prototype.bind = function () {
            }, i.prototype.position = function (t, e) {
            }, i.prototype.destroy = function () {
                this.$dropdown.remove()
            }, i
        }), l.define("select2/dropdown/search", ["jquery", "../utils"], function (s, t) {
            function e() {
            }

            return e.prototype.render = function (t) {
                var e = t.call(this),
                    t = s('<span class="select2-search select2-search--dropdown"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" /></span>');
                return this.$searchContainer = t, this.$search = t.find("input"), e.prepend(t), e
            }, e.prototype.bind = function (t, e, i) {
                var n = this, o = e.id + "-results";
                t.call(this, e, i), this.$search.on("keydown", function (t) {
                    n.trigger("keypress", t), n._keyUpPrevented = t.isDefaultPrevented()
                }), this.$search.on("input", function (t) {
                    s(this).off("keyup")
                }), this.$search.on("keyup input", function (t) {
                    n.handleSearch(t)
                }), e.on("open", function () {
                    n.$search.attr("tabindex", 0), n.$search.attr("aria-controls", o), n.$search.trigger("focus"), window.setTimeout(function () {
                        n.$search.trigger("focus")
                    }, 0)
                }), e.on("close", function () {
                    n.$search.attr("tabindex", -1), n.$search.removeAttr("aria-controls"), n.$search.removeAttr("aria-activedescendant"), n.$search.val(""), n.$search.trigger("blur")
                }), e.on("focus", function () {
                    e.isOpen() || n.$search.trigger("focus")
                }), e.on("results:all", function (t) {
                    null != t.query.term && "" !== t.query.term || (n.showSearch(t) ? n.$searchContainer.removeClass("select2-search--hide") : n.$searchContainer.addClass("select2-search--hide"))
                }), e.on("results:focus", function (t) {
                    t.data._resultId ? n.$search.attr("aria-activedescendant", t.data._resultId) : n.$search.removeAttr("aria-activedescendant")
                })
            }, e.prototype.handleSearch = function (t) {
                var e;
                this._keyUpPrevented || (e = this.$search.val(), this.trigger("query", {term: e})), this._keyUpPrevented = !1
            }, e.prototype.showSearch = function (t, e) {
                return !0
            }, e
        }), l.define("select2/dropdown/hidePlaceholder", [], function () {
            function t(t, e, i, n) {
                this.placeholder = this.normalizePlaceholder(i.get("placeholder")), t.call(this, e, i, n)
            }

            return t.prototype.append = function (t, e) {
                e.results = this.removePlaceholder(e.results), t.call(this, e)
            }, t.prototype.normalizePlaceholder = function (t, e) {
                return e = "string" == typeof e ? {id: "", text: e} : e
            }, t.prototype.removePlaceholder = function (t, e) {
                for (var i = e.slice(0), n = e.length - 1; 0 <= n; n--) {
                    var o = e[n];
                    this.placeholder.id === o.id && i.splice(n, 1)
                }
                return i
            }, t
        }), l.define("select2/dropdown/infiniteScroll", ["jquery"], function (i) {
            function t(t, e, i, n) {
                this.lastParams = {}, t.call(this, e, i, n), this.$loadingMore = this.createLoadingMore(), this.loading = !1
            }

            return t.prototype.append = function (t, e) {
                this.$loadingMore.remove(), this.loading = !1, t.call(this, e), this.showLoadingMore(e) && (this.$results.append(this.$loadingMore), this.loadMoreIfNeeded())
            }, t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), e.on("query", function (t) {
                    n.lastParams = t, n.loading = !0
                }), e.on("query:append", function (t) {
                    n.lastParams = t, n.loading = !0
                }), this.$results.on("scroll", this.loadMoreIfNeeded.bind(this))
            }, t.prototype.loadMoreIfNeeded = function () {
                var t = i.contains(document.documentElement, this.$loadingMore[0]);
                !this.loading && t && (t = this.$results.offset().top + this.$results.outerHeight(!1), this.$loadingMore.offset().top + this.$loadingMore.outerHeight(!1) <= t + 50 && this.loadMore())
            }, t.prototype.loadMore = function () {
                this.loading = !0;
                var t = i.extend({}, {page: 1}, this.lastParams);
                t.page++, this.trigger("query:append", t)
            }, t.prototype.showLoadingMore = function (t, e) {
                return e.pagination && e.pagination.more
            }, t.prototype.createLoadingMore = function () {
                var t = i('<li class="select2-results__option select2-results__option--load-more"role="option" aria-disabled="true"></li>'),
                    e = this.options.get("translations").get("loadingMore");
                return t.html(e(this.lastParams)), t
            }, t
        }), l.define("select2/dropdown/attachBody", ["jquery", "../utils"], function (d, a) {
            function t(t, e, i) {
                this.$dropdownParent = d(i.get("dropdownParent") || document.body), t.call(this, e, i)
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), e.on("open", function () {
                    n._showDropdown(), n._attachPositioningHandler(e), n._bindContainerResultHandlers(e)
                }), e.on("close", function () {
                    n._hideDropdown(), n._detachPositioningHandler(e)
                }), this.$dropdownContainer.on("mousedown", function (t) {
                    t.stopPropagation()
                })
            }, t.prototype.destroy = function (t) {
                t.call(this), this.$dropdownContainer.remove()
            }, t.prototype.position = function (t, e, i) {
                e.attr("class", i.attr("class")), e.removeClass("select2"), e.addClass("select2-container--open"), e.css({
                    position: "absolute",
                    top: -999999
                }), this.$container = i
            }, t.prototype.render = function (t) {
                var e = d("<span></span>"), t = t.call(this);
                return e.append(t), this.$dropdownContainer = e
            }, t.prototype._hideDropdown = function (t) {
                this.$dropdownContainer.detach()
            }, t.prototype._bindContainerResultHandlers = function (t, e) {
                var i;
                this._containerResultsHandlersBound || (i = this, e.on("results:all", function () {
                    i._positionDropdown(), i._resizeDropdown()
                }), e.on("results:append", function () {
                    i._positionDropdown(), i._resizeDropdown()
                }), e.on("results:message", function () {
                    i._positionDropdown(), i._resizeDropdown()
                }), e.on("select", function () {
                    i._positionDropdown(), i._resizeDropdown()
                }), e.on("unselect", function () {
                    i._positionDropdown(), i._resizeDropdown()
                }), this._containerResultsHandlersBound = !0)
            }, t.prototype._attachPositioningHandler = function (t, e) {
                var i = this, n = "scroll.select2." + e.id, o = "resize.select2." + e.id,
                    s = "orientationchange.select2." + e.id, e = this.$container.parents().filter(a.hasScroll);
                e.each(function () {
                    a.StoreData(this, "select2-scroll-position", {x: d(this).scrollLeft(), y: d(this).scrollTop()})
                }), e.on(n, function (t) {
                    var e = a.GetData(this, "select2-scroll-position");
                    d(this).scrollTop(e.y)
                }), d(window).on(n + " " + o + " " + s, function (t) {
                    i._positionDropdown(), i._resizeDropdown()
                })
            }, t.prototype._detachPositioningHandler = function (t, e) {
                var i = "scroll.select2." + e.id, n = "resize.select2." + e.id, e = "orientationchange.select2." + e.id;
                this.$container.parents().filter(a.hasScroll).off(i), d(window).off(i + " " + n + " " + e)
            }, t.prototype._positionDropdown = function () {
                var t = d(window), e = this.$dropdown.hasClass("select2-dropdown--above"),
                    i = this.$dropdown.hasClass("select2-dropdown--below"), n = null, o = this.$container.offset();
                o.bottom = o.top + this.$container.outerHeight(!1);
                var s = {height: this.$container.outerHeight(!1)};
                s.top = o.top, s.bottom = o.top + s.height;
                var a = this.$dropdown.outerHeight(!1), r = t.scrollTop(), l = t.scrollTop() + t.height(),
                    c = r < o.top - a, t = l > o.bottom + a, r = {left: o.left, top: s.bottom},
                    l = this.$dropdownParent;
                "static" === l.css("position") && (l = l.offsetParent());
                o = {top: 0, left: 0};
                (d.contains(document.body, l[0]) || l[0].isConnected) && (o = l.offset()), r.top -= o.top, r.left -= o.left, e || i || (n = "below"), t || !c || e ? !c && t && e && (n = "below") : n = "above", ("above" == n || e && "below" !== n) && (r.top = s.top - o.top - a), null != n && (this.$dropdown.removeClass("select2-dropdown--below select2-dropdown--above").addClass("select2-dropdown--" + n), this.$container.removeClass("select2-container--below select2-container--above").addClass("select2-container--" + n)), this.$dropdownContainer.css(r)
            }, t.prototype._resizeDropdown = function () {
                var t = {width: this.$container.outerWidth(!1) + "px"};
                this.options.get("dropdownAutoWidth") && (t.minWidth = t.width, t.position = "relative", t.width = "auto"), this.$dropdown.css(t)
            }, t.prototype._showDropdown = function (t) {
                this.$dropdownContainer.appendTo(this.$dropdownParent), this._positionDropdown(), this._resizeDropdown()
            }, t
        }), l.define("select2/dropdown/minimumResultsForSearch", [], function () {
            function t(t, e, i, n) {
                this.minimumResultsForSearch = i.get("minimumResultsForSearch"), this.minimumResultsForSearch < 0 && (this.minimumResultsForSearch = 1 / 0), t.call(this, e, i, n)
            }

            return t.prototype.showSearch = function (t, e) {
                return !(function t(e) {
                    for (var i = 0, n = 0; n < e.length; n++) {
                        var o = e[n];
                        o.children ? i += t(o.children) : i++
                    }
                    return i
                }(e.data.results) < this.minimumResultsForSearch) && t.call(this, e)
            }, t
        }), l.define("select2/dropdown/selectOnClose", ["../utils"], function (n) {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), e.on("close", function (t) {
                    n._handleSelectOnClose(t)
                })
            }, t.prototype._handleSelectOnClose = function (t, e) {
                if (e && null != e.originalSelect2Event) {
                    var i = e.originalSelect2Event;
                    if ("select" === i._type || "unselect" === i._type) return
                }
                var i = this.getHighlightedResults();
                i.length < 1 || (null != (i = n.GetData(i[0], "data")).element && i.element.selected || null == i.element && i.selected || this.trigger("select", {data: i}))
            }, t
        }), l.define("select2/dropdown/closeOnSelect", [], function () {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                var n = this;
                t.call(this, e, i), e.on("select", function (t) {
                    n._selectTriggered(t)
                }), e.on("unselect", function (t) {
                    n._selectTriggered(t)
                })
            }, t.prototype._selectTriggered = function (t, e) {
                var i = e.originalEvent;
                i && (i.ctrlKey || i.metaKey) || this.trigger("close", {originalEvent: i, originalSelect2Event: e})
            }, t
        }), l.define("select2/i18n/en", [], function () {
            return {
                errorLoading: function () {
                    return "The results could not be loaded."
                }, inputTooLong: function (t) {
                    var e = t.input.length - t.maximum, t = "Please delete " + e + " character";
                    return 1 != e && (t += "s"), t
                }, inputTooShort: function (t) {
                    return "Please enter " + (t.minimum - t.input.length) + " or more characters"
                }, loadingMore: function () {
                    return "Loading more results…"
                }, maximumSelected: function (t) {
                    var e = "You can only select " + t.maximum + " item";
                    return 1 != t.maximum && (e += "s"), e
                }, noResults: function () {
                    return "No results found"
                }, searching: function () {
                    return "Searching…"
                }, removeAllItems: function () {
                    return "Remove all items"
                }
            }
        }), l.define("select2/defaults", ["jquery", "require", "./results", "./selection/single", "./selection/multiple", "./selection/placeholder", "./selection/allowClear", "./selection/search", "./selection/eventRelay", "./utils", "./translation", "./diacritics", "./data/select", "./data/array", "./data/ajax", "./data/tags", "./data/tokenizer", "./data/minimumInputLength", "./data/maximumInputLength", "./data/maximumSelectionLength", "./dropdown", "./dropdown/search", "./dropdown/hidePlaceholder", "./dropdown/infiniteScroll", "./dropdown/attachBody", "./dropdown/minimumResultsForSearch", "./dropdown/selectOnClose", "./dropdown/closeOnSelect", "./i18n/en"], function (l, r, c, d, u, h, p, f, m, g, a, e, v, b, y, w, x, _, C, S, T, $, E, k, I, O, z, A, t) {
            function i() {
                this.reset()
            }

            return i.prototype.apply = function (t) {
                var e, i, n;
                null == (t = l.extend(!0, {}, this.defaults, t)).dataAdapter && (null != t.ajax ? t.dataAdapter = y : null != t.data ? t.dataAdapter = b : t.dataAdapter = v, 0 < t.minimumInputLength && (t.dataAdapter = g.Decorate(t.dataAdapter, _)), 0 < t.maximumInputLength && (t.dataAdapter = g.Decorate(t.dataAdapter, C)), 0 < t.maximumSelectionLength && (t.dataAdapter = g.Decorate(t.dataAdapter, S)), t.tags && (t.dataAdapter = g.Decorate(t.dataAdapter, w)), null == t.tokenSeparators && null == t.tokenizer || (t.dataAdapter = g.Decorate(t.dataAdapter, x)), null != t.query && (e = r(t.amdBase + "compat/query"), t.dataAdapter = g.Decorate(t.dataAdapter, e)), null != t.initSelection && (i = r(t.amdBase + "compat/initSelection"), t.dataAdapter = g.Decorate(t.dataAdapter, i))), null == t.resultsAdapter && (t.resultsAdapter = c, null != t.ajax && (t.resultsAdapter = g.Decorate(t.resultsAdapter, k)), null != t.placeholder && (t.resultsAdapter = g.Decorate(t.resultsAdapter, E)), t.selectOnClose && (t.resultsAdapter = g.Decorate(t.resultsAdapter, z))), null == t.dropdownAdapter && (t.multiple ? t.dropdownAdapter = T : (i = g.Decorate(T, $), t.dropdownAdapter = i), 0 !== t.minimumResultsForSearch && (t.dropdownAdapter = g.Decorate(t.dropdownAdapter, O)), t.closeOnSelect && (t.dropdownAdapter = g.Decorate(t.dropdownAdapter, A)), null == t.dropdownCssClass && null == t.dropdownCss && null == t.adaptDropdownCssClass || (n = r(t.amdBase + "compat/dropdownCss"), t.dropdownAdapter = g.Decorate(t.dropdownAdapter, n)), t.dropdownAdapter = g.Decorate(t.dropdownAdapter, I)), null == t.selectionAdapter && (t.multiple ? t.selectionAdapter = u : t.selectionAdapter = d, null != t.placeholder && (t.selectionAdapter = g.Decorate(t.selectionAdapter, h)), t.allowClear && (t.selectionAdapter = g.Decorate(t.selectionAdapter, p)), t.multiple && (t.selectionAdapter = g.Decorate(t.selectionAdapter, f)), null == t.containerCssClass && null == t.containerCss && null == t.adaptContainerCssClass || (n = r(t.amdBase + "compat/containerCss"), t.selectionAdapter = g.Decorate(t.selectionAdapter, n)), t.selectionAdapter = g.Decorate(t.selectionAdapter, m)), t.language = this._resolveLanguage(t.language), t.language.push("en");
                for (var o = [], s = 0; s < t.language.length; s++) {
                    var a = t.language[s];
                    -1 === o.indexOf(a) && o.push(a)
                }
                return t.language = o, t.translations = this._processTranslations(t.language, t.debug), t
            }, i.prototype.reset = function () {
                function r(t) {
                    return t.replace(/[^\u0000-\u007E]/g, function (t) {
                        return e[t] || t
                    })
                }

                this.defaults = {
                    amdBase: "./",
                    amdLanguageBase: "./i18n/",
                    closeOnSelect: !0,
                    debug: !1,
                    dropdownAutoWidth: !1,
                    escapeMarkup: g.escapeMarkup,
                    language: {},
                    matcher: function t(e, i) {
                        if ("" === l.trim(e.term)) return i;
                        if (i.children && 0 < i.children.length) {
                            for (var n = l.extend(!0, {}, i), o = i.children.length - 1; 0 <= o; o--) null == t(e, i.children[o]) && n.children.splice(o, 1);
                            return 0 < n.children.length ? n : t(e, n)
                        }
                        var s = r(i.text).toUpperCase(), a = r(e.term).toUpperCase();
                        return -1 < s.indexOf(a) ? i : null
                    },
                    minimumInputLength: 0,
                    maximumInputLength: 0,
                    maximumSelectionLength: 0,
                    minimumResultsForSearch: 0,
                    selectOnClose: !1,
                    scrollAfterSelect: !1,
                    sorter: function (t) {
                        return t
                    },
                    templateResult: function (t) {
                        return t.text
                    },
                    templateSelection: function (t) {
                        return t.text
                    },
                    theme: "default",
                    width: "resolve"
                }
            }, i.prototype.applyFromElement = function (t, e) {
                var i = t.language, n = this.defaults.language, o = e.prop("lang"),
                    e = e.closest("[lang]").prop("lang"),
                    e = Array.prototype.concat.call(this._resolveLanguage(o), this._resolveLanguage(i), this._resolveLanguage(n), this._resolveLanguage(e));
                return t.language = e, t
            }, i.prototype._resolveLanguage = function (t) {
                if (!t) return [];
                if (l.isEmptyObject(t)) return [];
                if (l.isPlainObject(t)) return [t];
                for (var e, i = l.isArray(t) ? t : [t], n = [], o = 0; o < i.length; o++) n.push(i[o]), "string" == typeof i[o] && 0 < i[o].indexOf("-") && (e = i[o].split("-")[0], n.push(e));
                return n
            }, i.prototype._processTranslations = function (t, e) {
                for (var i = new a, n = 0; n < t.length; n++) {
                    var o = new a, s = t[n];
                    if ("string" == typeof s) try {
                        o = a.loadPath(s)
                    } catch (t) {
                        try {
                            s = this.defaults.amdLanguageBase + s, o = a.loadPath(s)
                        } catch (t) {
                            e && window.console && console.warn && console.warn('Select2: The language file for "' + s + '" could not be automatically loaded. A fallback will be used instead.')
                        }
                    } else o = l.isPlainObject(s) ? new a(s) : s;
                    i.extend(o)
                }
                return i
            }, i.prototype.set = function (t, e) {
                var i = {};
                i[l.camelCase(t)] = e;
                i = g._convertData(i);
                l.extend(!0, this.defaults, i)
            }, new i
        }), l.define("select2/options", ["require", "jquery", "./defaults", "./utils"], function (i, c, n, d) {
            function t(t, e) {
                this.options = t, null != e && this.fromElement(e), null != e && (this.options = n.applyFromElement(this.options, e)), this.options = n.apply(this.options), e && e.is("input") && (e = i(this.get("amdBase") + "compat/inputData"), this.options.dataAdapter = d.Decorate(this.options.dataAdapter, e))
            }

            return t.prototype.fromElement = function (t) {
                var e = ["select2"];
                null == this.options.multiple && (this.options.multiple = t.prop("multiple")), null == this.options.disabled && (this.options.disabled = t.prop("disabled")), null == this.options.dir && (t.prop("dir") ? this.options.dir = t.prop("dir") : t.closest("[dir]").prop("dir") ? this.options.dir = t.closest("[dir]").prop("dir") : this.options.dir = "ltr"), t.prop("disabled", this.options.disabled), t.prop("multiple", this.options.multiple), d.GetData(t[0], "select2Tags") && (this.options.debug && window.console && console.warn && console.warn('Select2: The `data-select2-tags` attribute has been changed to use the `data-data` and `data-tags="true"` attributes and will be removed in future versions of Select2.'), d.StoreData(t[0], "data", d.GetData(t[0], "select2Tags")), d.StoreData(t[0], "tags", !0)), d.GetData(t[0], "ajaxUrl") && (this.options.debug && window.console && console.warn && console.warn("Select2: The `data-ajax-url` attribute has been changed to `data-ajax--url` and support for the old attribute will be removed in future versions of Select2."), t.attr("ajax--url", d.GetData(t[0], "ajaxUrl")), d.StoreData(t[0], "ajax-Url", d.GetData(t[0], "ajaxUrl")));
                var i = {};

                function n(t, e) {
                    return e.toUpperCase()
                }

                for (var o = 0; o < t[0].attributes.length; o++) {
                    var s, a = t[0].attributes[o].name;
                    "data-" == a.substr(0, "data-".length) && (s = a.substring("data-".length), a = d.GetData(t[0], s), i[s.replace(/-([a-z])/g, n)] = a)
                }
                c.fn.jquery && "1." == c.fn.jquery.substr(0, 2) && t[0].dataset && (i = c.extend(!0, {}, t[0].dataset, i));
                var r, l = c.extend(!0, {}, d.GetData(t[0]), i);
                for (r in l = d._convertData(l)) -1 < c.inArray(r, e) || (c.isPlainObject(this.options[r]) ? c.extend(this.options[r], l[r]) : this.options[r] = l[r]);
                return this
            }, t.prototype.get = function (t) {
                return this.options[t]
            }, t.prototype.set = function (t, e) {
                this.options[t] = e
            }, t
        }), l.define("select2/core", ["jquery", "./options", "./utils", "./keys"], function (o, s, a, n) {
            function r(t, e) {
                null != a.GetData(t[0], "select2") && a.GetData(t[0], "select2").destroy(), this.$element = t, this.id = this._generateId(t), this.options = new s(e = e || {}, t), r.__super__.constructor.call(this);
                var i = t.attr("tabindex") || 0;
                a.StoreData(t[0], "old-tabindex", i), t.attr("tabindex", "-1"), e = this.options.get("dataAdapter"), this.dataAdapter = new e(t, this.options), i = this.render(), this._placeContainer(i), e = this.options.get("selectionAdapter"), this.selection = new e(t, this.options), this.$selection = this.selection.render(), this.selection.position(this.$selection, i), e = this.options.get("dropdownAdapter"), this.dropdown = new e(t, this.options), this.$dropdown = this.dropdown.render(), this.dropdown.position(this.$dropdown, i), i = this.options.get("resultsAdapter"), this.results = new i(t, this.options, this.dataAdapter), this.$results = this.results.render(), this.results.position(this.$results, this.$dropdown);
                var n = this;
                this._bindAdapters(), this._registerDomEvents(), this._registerDataEvents(), this._registerSelectionEvents(), this._registerDropdownEvents(), this._registerResultsEvents(), this._registerEvents(), this.dataAdapter.current(function (t) {
                    n.trigger("selection:update", {data: t})
                }), t.addClass("select2-hidden-accessible"), t.attr("aria-hidden", "true"), this._syncAttributes(), a.StoreData(t[0], "select2", this), t.data("select2", this)
            }

            return a.Extend(r, a.Observable), r.prototype._generateId = function (t) {
                return "select2-" + (null != t.attr("id") ? t.attr("id") : null != t.attr("name") ? t.attr("name") + "-" + a.generateChars(2) : a.generateChars(4)).replace(/(:|\.|\[|\]|,)/g, "")
            }, r.prototype._placeContainer = function (t) {
                t.insertAfter(this.$element);
                var e = this._resolveWidth(this.$element, this.options.get("width"));
                null != e && t.css("width", e)
            }, r.prototype._resolveWidth = function (t, e) {
                var i = /^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i;
                if ("resolve" == e) {
                    var n = this._resolveWidth(t, "style");
                    return null != n ? n : this._resolveWidth(t, "element")
                }
                if ("element" == e) {
                    n = t.outerWidth(!1);
                    return n <= 0 ? "auto" : n + "px"
                }
                if ("style" != e) return "computedstyle" != e ? e : window.getComputedStyle(t[0]).width;
                t = t.attr("style");
                if ("string" != typeof t) return null;
                for (var o = t.split(";"), s = 0, a = o.length; s < a; s += 1) {
                    var r = o[s].replace(/\s/g, "").match(i);
                    if (null !== r && 1 <= r.length) return r[1]
                }
                return null
            }, r.prototype._bindAdapters = function () {
                this.dataAdapter.bind(this, this.$container), this.selection.bind(this, this.$container), this.dropdown.bind(this, this.$container), this.results.bind(this, this.$container)
            }, r.prototype._registerDomEvents = function () {
                var e = this;
                this.$element.on("change.select2", function () {
                    e.dataAdapter.current(function (t) {
                        e.trigger("selection:update", {data: t})
                    })
                }), this.$element.on("focus.select2", function (t) {
                    e.trigger("focus", t)
                }), this._syncA = a.bind(this._syncAttributes, this), this._syncS = a.bind(this._syncSubtree, this), this.$element[0].attachEvent && this.$element[0].attachEvent("onpropertychange", this._syncA);
                var t = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
                null != t ? (this._observer = new t(function (t) {
                    o.each(t, e._syncA), o.each(t, e._syncS)
                }), this._observer.observe(this.$element[0], {
                    attributes: !0,
                    childList: !0,
                    subtree: !1
                })) : this.$element[0].addEventListener && (this.$element[0].addEventListener("DOMAttrModified", e._syncA, !1), this.$element[0].addEventListener("DOMNodeInserted", e._syncS, !1), this.$element[0].addEventListener("DOMNodeRemoved", e._syncS, !1))
            }, r.prototype._registerDataEvents = function () {
                var i = this;
                this.dataAdapter.on("*", function (t, e) {
                    i.trigger(t, e)
                })
            }, r.prototype._registerSelectionEvents = function () {
                var i = this, n = ["toggle", "focus"];
                this.selection.on("toggle", function () {
                    i.toggleDropdown()
                }), this.selection.on("focus", function (t) {
                    i.focus(t)
                }), this.selection.on("*", function (t, e) {
                    -1 === o.inArray(t, n) && i.trigger(t, e)
                })
            }, r.prototype._registerDropdownEvents = function () {
                var i = this;
                this.dropdown.on("*", function (t, e) {
                    i.trigger(t, e)
                })
            }, r.prototype._registerResultsEvents = function () {
                var i = this;
                this.results.on("*", function (t, e) {
                    i.trigger(t, e)
                })
            }, r.prototype._registerEvents = function () {
                var i = this;
                this.on("open", function () {
                    i.$container.addClass("select2-container--open")
                }), this.on("close", function () {
                    i.$container.removeClass("select2-container--open")
                }), this.on("enable", function () {
                    i.$container.removeClass("select2-container--disabled")
                }), this.on("disable", function () {
                    i.$container.addClass("select2-container--disabled")
                }), this.on("blur", function () {
                    i.$container.removeClass("select2-container--focus")
                }), this.on("query", function (e) {
                    i.isOpen() || i.trigger("open", {}), this.dataAdapter.query(e, function (t) {
                        i.trigger("results:all", {data: t, query: e})
                    })
                }), this.on("query:append", function (e) {
                    this.dataAdapter.query(e, function (t) {
                        i.trigger("results:append", {data: t, query: e})
                    })
                }), this.on("keypress", function (t) {
                    var e = t.which;
                    i.isOpen() ? e === n.ESC || e === n.TAB || e === n.UP && t.altKey ? (i.close(), t.preventDefault()) : e === n.ENTER ? (i.trigger("results:select", {}), t.preventDefault()) : e === n.SPACE && t.ctrlKey ? (i.trigger("results:toggle", {}), t.preventDefault()) : e === n.UP ? (i.trigger("results:previous", {}), t.preventDefault()) : e === n.DOWN && (i.trigger("results:next", {}), t.preventDefault()) : (e === n.ENTER || e === n.SPACE || e === n.DOWN && t.altKey) && (i.open(), t.preventDefault())
                })
            }, r.prototype._syncAttributes = function () {
                this.options.set("disabled", this.$element.prop("disabled")), this.options.get("disabled") ? (this.isOpen() && this.close(), this.trigger("disable", {})) : this.trigger("enable", {})
            }, r.prototype._syncSubtree = function (t, e) {
                var i = !1, n = this;
                if (!t || !t.target || "OPTION" === t.target.nodeName || "OPTGROUP" === t.target.nodeName) {
                    if (e) if (e.addedNodes && 0 < e.addedNodes.length) for (var o = 0; o < e.addedNodes.length; o++) e.addedNodes[o].selected && (i = !0); else e.removedNodes && 0 < e.removedNodes.length && (i = !0); else i = !0;
                    i && this.dataAdapter.current(function (t) {
                        n.trigger("selection:update", {data: t})
                    })
                }
            }, r.prototype.trigger = function (t, e) {
                var i = r.__super__.trigger, n = {
                    open: "opening",
                    close: "closing",
                    select: "selecting",
                    unselect: "unselecting",
                    clear: "clearing"
                };
                if (void 0 === e && (e = {}), t in n) {
                    var o = {prevented: !1, name: t, args: e};
                    if (i.call(this, n[t], o), o.prevented) return void (e.prevented = !0)
                }
                i.call(this, t, e)
            }, r.prototype.toggleDropdown = function () {
                this.options.get("disabled") || (this.isOpen() ? this.close() : this.open())
            }, r.prototype.open = function () {
                this.isOpen() || this.trigger("query", {})
            }, r.prototype.close = function () {
                this.isOpen() && this.trigger("close", {})
            }, r.prototype.isOpen = function () {
                return this.$container.hasClass("select2-container--open")
            }, r.prototype.hasFocus = function () {
                return this.$container.hasClass("select2-container--focus")
            }, r.prototype.focus = function (t) {
                this.hasFocus() || (this.$container.addClass("select2-container--focus"), this.trigger("focus", {}))
            }, r.prototype.enable = function (t) {
                this.options.get("debug") && window.console && console.warn && console.warn('Select2: The `select2("enable")` method has been deprecated and will be removed in later Select2 versions. Use $element.prop("disabled") instead.');
                t = !(t = null == t || 0 === t.length ? [!0] : t)[0];
                this.$element.prop("disabled", t)
            }, r.prototype.data = function () {
                this.options.get("debug") && 0 < arguments.length && window.console && console.warn && console.warn('Select2: Data can no longer be set using `select2("data")`. You should consider setting the value instead using `$element.val()`.');
                var e = [];
                return this.dataAdapter.current(function (t) {
                    e = t
                }), e
            }, r.prototype.val = function (t) {
                if (this.options.get("debug") && window.console && console.warn && console.warn('Select2: The `select2("val")` method has been deprecated and will be removed in later Select2 versions. Use $element.val() instead.'), null == t || 0 === t.length) return this.$element.val();
                t = t[0];
                o.isArray(t) && (t = o.map(t, function (t) {
                    return t.toString()
                })), this.$element.val(t).trigger("change")
            }, r.prototype.destroy = function () {
                this.$container.remove(), this.$element[0].detachEvent && this.$element[0].detachEvent("onpropertychange", this._syncA), null != this._observer ? (this._observer.disconnect(), this._observer = null) : this.$element[0].removeEventListener && (this.$element[0].removeEventListener("DOMAttrModified", this._syncA, !1), this.$element[0].removeEventListener("DOMNodeInserted", this._syncS, !1), this.$element[0].removeEventListener("DOMNodeRemoved", this._syncS, !1)), this._syncA = null, this._syncS = null, this.$element.off(".select2"), this.$element.attr("tabindex", a.GetData(this.$element[0], "old-tabindex")), this.$element.removeClass("select2-hidden-accessible"), this.$element.attr("aria-hidden", "false"), a.RemoveData(this.$element[0]), this.$element.removeData("select2"), this.dataAdapter.destroy(), this.selection.destroy(), this.dropdown.destroy(), this.results.destroy(), this.dataAdapter = null, this.selection = null, this.dropdown = null, this.results = null
            }, r.prototype.render = function () {
                var t = o('<span class="select2 select2-container"><span class="selection"></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>');
                return t.attr("dir", this.options.get("dir")), this.$container = t, this.$container.addClass("select2-container--" + this.options.get("theme")), a.StoreData(t[0], "element", this.$element), t
            }, r
        }), l.define("select2/compat/utils", ["jquery"], function (a) {
            return {
                syncCssClasses: function (t, e, i) {
                    var n, o, s = [];
                    (n = a.trim(t.attr("class"))) && a((n = "" + n).split(/\s+/)).each(function () {
                        0 === this.indexOf("select2-") && s.push(this)
                    }), (n = a.trim(e.attr("class"))) && a((n = "" + n).split(/\s+/)).each(function () {
                        0 !== this.indexOf("select2-") && null != (o = i(this)) && s.push(o)
                    }), t.attr("class", s.join(" "))
                }
            }
        }), l.define("select2/compat/containerCss", ["jquery", "./utils"], function (s, a) {
            function r(t) {
                return null
            }

            function t() {
            }

            return t.prototype.render = function (t) {
                var e = t.call(this), i = this.options.get("containerCssClass") || "";
                s.isFunction(i) && (i = i(this.$element));
                var n, o = this.options.get("adaptContainerCssClass");
                o = o || r, -1 !== i.indexOf(":all:") && (i = i.replace(":all:", ""), n = o, o = function (t) {
                    var e = n(t);
                    return null != e ? e + " " + t : t
                });
                t = this.options.get("containerCss") || {};
                return s.isFunction(t) && (t = t(this.$element)), a.syncCssClasses(e, this.$element, o), e.css(t), e.addClass(i), e
            }, t
        }), l.define("select2/compat/dropdownCss", ["jquery", "./utils"], function (s, a) {
            function r(t) {
                return null
            }

            function t() {
            }

            return t.prototype.render = function (t) {
                var e = t.call(this), i = this.options.get("dropdownCssClass") || "";
                s.isFunction(i) && (i = i(this.$element));
                var n, o = this.options.get("adaptDropdownCssClass");
                o = o || r, -1 !== i.indexOf(":all:") && (i = i.replace(":all:", ""), n = o, o = function (t) {
                    var e = n(t);
                    return null != e ? e + " " + t : t
                });
                t = this.options.get("dropdownCss") || {};
                return s.isFunction(t) && (t = t(this.$element)), a.syncCssClasses(e, this.$element, o), e.css(t), e.addClass(i), e
            }, t
        }), l.define("select2/compat/initSelection", ["jquery"], function (n) {
            function t(t, e, i) {
                i.get("debug") && window.console && console.warn && console.warn("Select2: The `initSelection` option has been deprecated in favor of a custom data adapter that overrides the `current` method. This method is now called multiple times instead of a single time when the instance is initialized. Support will be removed for the `initSelection` option in future versions of Select2"), this.initSelection = i.get("initSelection"), this._isInitialized = !1, t.call(this, e, i)
            }

            return t.prototype.current = function (t, e) {
                var i = this;
                this._isInitialized ? t.call(this, e) : this.initSelection.call(null, this.$element, function (t) {
                    i._isInitialized = !0, n.isArray(t) || (t = [t]), e(t)
                })
            }, t
        }), l.define("select2/compat/inputData", ["jquery", "../utils"], function (s, i) {
            function t(t, e, i) {
                this._currentData = [], this._valueSeparator = i.get("valueSeparator") || ",", "hidden" === e.prop("type") && i.get("debug") && console && console.warn && console.warn("Select2: Using a hidden input with Select2 is no longer supported and may stop working in the future. It is recommended to use a `<select>` element instead."), t.call(this, e, i)
            }

            return t.prototype.current = function (t, e) {
                for (var i = [], n = 0; n < this._currentData.length; n++) {
                    var o = this._currentData[n];
                    i.push.apply(i, function t(e, i) {
                        var n = [];
                        return e.selected || -1 !== s.inArray(e.id, i) ? (e.selected = !0, n.push(e)) : e.selected = !1, e.children && n.push.apply(n, t(e.children, i)), n
                    }(o, this.$element.val().split(this._valueSeparator)))
                }
                e(i)
            }, t.prototype.select = function (t, e) {
                var i;
                this.options.get("multiple") ? (i = this.$element.val(), i += this._valueSeparator + e.id, this.$element.val(i)) : (this.current(function (t) {
                    s.map(t, function (t) {
                        t.selected = !1
                    })
                }), this.$element.val(e.id)), this.$element.trigger("change")
            }, t.prototype.unselect = function (t, o) {
                var s = this;
                o.selected = !1, this.current(function (t) {
                    for (var e = [], i = 0; i < t.length; i++) {
                        var n = t[i];
                        o.id != n.id && e.push(n.id)
                    }
                    s.$element.val(e.join(s._valueSeparator)), s.$element.trigger("change")
                })
            }, t.prototype.query = function (t, e, i) {
                for (var n = [], o = 0; o < this._currentData.length; o++) {
                    var s = this._currentData[o], s = this.matches(e, s);
                    null !== s && n.push(s)
                }
                i({results: n})
            }, t.prototype.addOptions = function (t, e) {
                e = s.map(e, function (t) {
                    return i.GetData(t[0], "data")
                });
                this._currentData.push.apply(this._currentData, e)
            }, t
        }), l.define("select2/compat/matcher", ["jquery"], function (a) {
            return function (s) {
                return function (t, e) {
                    var i = a.extend(!0, {}, e);
                    if (null == t.term || "" === a.trim(t.term)) return i;
                    if (e.children) {
                        for (var n = e.children.length - 1; 0 <= n; n--) {
                            var o = e.children[n];
                            s(t.term, o.text, o) || i.children.splice(n, 1)
                        }
                        if (0 < i.children.length) return i
                    }
                    return s(t.term, e.text, e) ? i : null
                }
            }
        }), l.define("select2/compat/query", [], function () {
            function t(t, e, i) {
                i.get("debug") && window.console && console.warn && console.warn("Select2: The `query` option has been deprecated in favor of a custom data adapter that overrides the `query` method. Support will be removed for the `query` option in future versions of Select2."), t.call(this, e, i)
            }

            return t.prototype.query = function (t, e, i) {
                e.callback = i, this.options.get("query").call(null, e)
            }, t
        }), l.define("select2/dropdown/attachContainer", [], function () {
            function t(t, e, i) {
                t.call(this, e, i)
            }

            return t.prototype.position = function (t, e, i) {
                i.find(".dropdown-wrapper").append(e), e.addClass("select2-dropdown--below"), i.addClass("select2-container--below")
            }, t
        }), l.define("select2/dropdown/stopPropagation", [], function () {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                t.call(this, e, i), this.$dropdown.on(["blur", "change", "click", "dblclick", "focus", "focusin", "focusout", "input", "keydown", "keyup", "keypress", "mousedown", "mouseenter", "mouseleave", "mousemove", "mouseover", "mouseup", "search", "touchend", "touchstart"].join(" "), function (t) {
                    t.stopPropagation()
                })
            }, t
        }), l.define("select2/selection/stopPropagation", [], function () {
            function t() {
            }

            return t.prototype.bind = function (t, e, i) {
                t.call(this, e, i), this.$selection.on(["blur", "change", "click", "dblclick", "focus", "focusin", "focusout", "input", "keydown", "keyup", "keypress", "mousedown", "mouseenter", "mouseleave", "mousemove", "mouseover", "mouseup", "search", "touchend", "touchstart"].join(" "), function (t) {
                    t.stopPropagation()
                })
            }, t
        }), r = function (d) {
            var u, h, t = ["wheel", "mousewheel", "DOMMouseScroll", "MozMousePixelScroll"],
                e = "onwheel" in document || 9 <= document.documentMode ? ["wheel"] : ["mousewheel", "DomMouseScroll", "MozMousePixelScroll"],
                p = Array.prototype.slice;
            if (d.event.fixHooks) for (var i = t.length; i;) d.event.fixHooks[t[--i]] = d.event.mouseHooks;
            var f = d.event.special.mousewheel = {
                version: "3.1.12", setup: function () {
                    if (this.addEventListener) for (var t = e.length; t;) this.addEventListener(e[--t], n, !1); else this.onmousewheel = n;
                    d.data(this, "mousewheel-line-height", f.getLineHeight(this)), d.data(this, "mousewheel-page-height", f.getPageHeight(this))
                }, teardown: function () {
                    if (this.removeEventListener) for (var t = e.length; t;) this.removeEventListener(e[--t], n, !1); else this.onmousewheel = null;
                    d.removeData(this, "mousewheel-line-height"), d.removeData(this, "mousewheel-page-height")
                }, getLineHeight: function (t) {
                    var e = d(t), t = e["offsetParent" in d.fn ? "offsetParent" : "parent"]();
                    return t.length || (t = d("body")), parseInt(t.css("fontSize"), 10) || parseInt(e.css("fontSize"), 10) || 16
                }, getPageHeight: function (t) {
                    return d(t).height()
                }, settings: {adjustOldDeltas: !0, normalizeOffset: !0}
            };

            function n(t) {
                var e, i = t || window.event, n = p.call(arguments, 1), o = 0, s = 0, a = 0, r = 0, l = 0;
                if ((t = d.event.fix(i)).type = "mousewheel", "detail" in i && (a = -1 * i.detail), "wheelDelta" in i && (a = i.wheelDelta), "wheelDeltaY" in i && (a = i.wheelDeltaY), "wheelDeltaX" in i && (s = -1 * i.wheelDeltaX), "axis" in i && i.axis === i.HORIZONTAL_AXIS && (s = -1 * a, a = 0), o = 0 === a ? s : a, "deltaY" in i && (o = a = -1 * i.deltaY), "deltaX" in i && (s = i.deltaX, 0 === a && (o = -1 * s)), 0 !== a || 0 !== s) {
                    1 === i.deltaMode ? (o *= e = d.data(this, "mousewheel-line-height"), a *= e, s *= e) : 2 === i.deltaMode && (o *= c = d.data(this, "mousewheel-page-height"), a *= c, s *= c);
                    var c = Math.max(Math.abs(a), Math.abs(s));
                    return (!h || c < h) && g(i, h = c) && (h /= 40), g(i, c) && (o /= 40, s /= 40, a /= 40), o = Math[1 <= o ? "floor" : "ceil"](o / h), s = Math[1 <= s ? "floor" : "ceil"](s / h), a = Math[1 <= a ? "floor" : "ceil"](a / h), f.settings.normalizeOffset && this.getBoundingClientRect && (c = this.getBoundingClientRect(), r = t.clientX - c.left, l = t.clientY - c.top), t.deltaX = s, t.deltaY = a, t.deltaFactor = h, t.offsetX = r, t.offsetY = l, t.deltaMode = 0, n.unshift(t, o, s, a), u && clearTimeout(u), u = setTimeout(m, 200), (d.event.dispatch || d.event.handle).apply(this, n)
                }
            }

            function m() {
                h = null
            }

            function g(t, e) {
                return f.settings.adjustOldDeltas && "mousewheel" === t.type && e % 120 == 0
            }

            d.fn.extend({
                mousewheel: function (t) {
                    return t ? this.bind("mousewheel", t) : this.trigger("mousewheel")
                }, unmousewheel: function (t) {
                    return this.unbind("mousewheel", t)
                }
            })
        }, "function" == typeof l.define && l.define.amd ? l.define("jquery-mousewheel", ["jquery"], r) : "object" == typeof exports ? module.exports = r : r(e), l.define("jquery.select2", ["jquery", "jquery-mousewheel", "./select2/core", "./select2/defaults", "./select2/utils"], function (o, t, s, e, a) {
            var r;
            return null == o.fn.select2 && (r = ["open", "close", "destroy"], o.fn.select2 = function (e) {
                if ("object" == typeof (e = e || {})) return this.each(function () {
                    var t = o.extend(!0, {}, e);
                    new s(o(this), t)
                }), this;
                if ("string" != typeof e) throw new Error("Invalid arguments for Select2: " + e);
                var i, n = Array.prototype.slice.call(arguments, 1);
                return this.each(function () {
                    var t = a.GetData(this, "select2");
                    null == t && window.console && console.error && console.error("The select2('" + e + "') method was called on an element that is not using Select2."), i = t[e].apply(t, n)
                }), -1 < o.inArray(e, r) ? this : i
            }), null == o.fn.select2.defaults && (o.fn.select2.defaults = e), s
        }), {define: l.define, require: l.require}), l = r.require("jquery.select2");

    function w(t, e) {
        return n.call(t, e)
    }

    function c(t, e) {
        var i, n, o, s, a, r, l, c, d, u, h = e && e.split("/"), p = v.map, f = p && p["*"] || {};
        if (t) {
            for (e = (t = t.split("/")).length - 1, v.nodeIdCompat && y.test(t[e]) && (t[e] = t[e].replace(y, "")), "." === t[0].charAt(0) && h && (t = h.slice(0, h.length - 1).concat(t)), c = 0; c < t.length; c++) if ("." === (u = t[c])) t.splice(c, 1), --c; else if (".." === u) {
                if (0 === c || 1 === c && ".." === t[2] || ".." === t[c - 1]) continue;
                0 < c && (t.splice(c - 1, 2), c -= 2)
            }
            t = t.join("/")
        }
        if ((h || f) && p) {
            for (c = (i = t.split("/")).length; 0 < c; --c) {
                if (n = i.slice(0, c).join("/"), h) for (d = h.length; 0 < d; --d) if (o = (o = p[h.slice(0, d).join("/")]) && o[n]) {
                    s = o, a = c;
                    break
                }
                if (s) break;
                !r && f && f[n] && (r = f[n], l = c)
            }
            !s && r && (s = r, a = l), s && (i.splice(0, a, s), t = i.join("/"))
        }
        return t
    }

    function x(e, i) {
        return function () {
            var t = o.call(arguments, 0);
            return "string" != typeof t[0] && 1 === t.length && t.push(null), a.apply(h, t.concat([e, i]))
        }
    }

    function _(t) {
        var e;
        if (w(g, t) && (e = g[t], delete g[t], b[t] = !0, s.apply(h, e)), !w(m, t) && !w(b, t)) throw new Error("No " + t);
        return m[t]
    }

    function d(t) {
        var e, i = t ? t.indexOf("!") : -1;
        return -1 < i && (e = t.substring(0, i), t = t.substring(i + 1, t.length)), [e, t]
    }

    function C(t) {
        return t ? d(t) : []
    }

    return e.fn.select2.amd = r, l
}), function (t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], t) : "object" == typeof module && module.exports ? module.exports = t(require("jquery")) : t(window.jQuery)
}(function (p) {
    "use strict";

    function f(t, e) {
        return null == t || 0 === t.length || e && "" === p.trim(t)
    }

    function s(t, e) {
        this.$element = p(t), this.init(e), this.listen()
    }

    s.prototype = {
        constructor: s, init: function (t) {
            var i = this, e = i.$element;
            p.each(t, function (t, e) {
                i[t] = e
            }), i.initCache(), i.enableCache = !!i.enableCache, f(i.addCss) || e.hasClass(i.addCss) || e.addClass(i.addCss), i.$pane = e.find(".tab-pane.active"), i.$content = e.find(".tab-content"), i.$tabs = e.find(".nav-tabs"), i.isVertical = e.hasClass("tabs-left") || e.hasClass("tabs-right"), i.isVerticalSide = i.isVertical && e.hasClass("tab-sideways"), i.isVertical && i.$content.css("min-height", i.$tabs.outerHeight() + 1 + "px")
        }, setTitle: function (t) {
            var e = p.trim(t.text()), i = this.isVertical,
                n = f(t.data("maxTitleLength")) ? this.maxTitleLength : t.data("maxTitleLength");
            i && e.length > n - 2 && f(t.attr("title")) && t.attr("title", e)
        }, listen: function () {
            var h = this, t = h.$element;
            t.find(".nav-tabs li.disabled").each(function () {
                p(this).find('[data-toggle="tab"]').removeAttr("data-toggle")
            }), t.find('.nav-tabs li [data-toggle="dropdown"]').each(function () {
                h.setTitle(p(this))
            }), t.find('.nav-tabs li [data-toggle="tab"]').each(function () {
                var d = p(this), u = d.closest("li");
                u.removeAttr("data-toggle"), h.setTitle(d), d.on("click", function (t) {
                    var e, i, n, o, s, a, r, l, c;
                    u.hasClass("disabled") ? t.preventDefault() : (e = p(this).attr("data-url"), i = this.hash, n = e + i, f(e) || h.enableCache && h.cache.exist(n) ? d.trigger("tabsX:click") : (t.preventDefault(), o = p(i), s = p(this), a = s, r = p(this).attr("data-loading-class") || "kv-tab-loading", t = s.closest(".dropdown"), l = h.successCallback[i] || null, c = h.errorCallback[i] || null, f(t.attr("class")) || (a = t.find(".dropdown-toggle")), e = p.extend(!0, {}, {
                        type: "post",
                        dataType: "json",
                        url: e,
                        beforeSend: function (t, e) {
                            o.html("<br><br><br>"), a.removeClass(r).addClass(r), d.trigger("tabsX:beforeSend", [t, e])
                        },
                        success: function (t, e, i) {
                            setTimeout(function () {
                                o.html(t), s.tab("show"), a.removeClass(r), h.enableCache && h.cache.set(n), l && "function" == typeof l && l(t, e, i), d.trigger("tabsX:success", [t, e, i])
                            }, 300)
                        },
                        error: function (t, e, i) {
                            c && "function" == typeof c && c(t, e, i), d.trigger("tabsX:error", [t, e, i])
                        },
                        complete: function (t, e) {
                            d.trigger("tabsX:click", [t, e])
                        }
                    }, h.ajaxSettings), p.ajax(e)))
                })
            })
        }, initCache: function () {
            var e = this, i = parseFloat(e.cacheTimeout);
            isNaN(i) && (i = 0), e.cache = {
                data: {}, create: function () {
                    return (new Date).getTime()
                }, exist: function (t) {
                    return !!e.cache.data[t] && e.cache.create() - e.cache.data[t] < i
                }, set: function (t) {
                    e.cache.data[t] = e.cache.create()
                }
            }
        }, flushCache: function (t) {
            var i = this;
            "object" != typeof (t = "string" == typeof t ? [t] : t) || f(t) ? i.cache.data = {} : Object.values(t).forEach(function (e) {
                Object.keys(i.cache.data).forEach(function (t) {
                    t.endsWith(e) && delete i.cache.data[t]
                })
            })
        }
    }, p.fn.tabsX = function (i) {
        var n = Array.apply(null, arguments), o = [];
        switch (n.shift(), this.each(function () {
            var t = p(this), e = t.data("tabsX");
            e || (e = new s(this, p.extend(!0, {}, p.fn.tabsX.defaults, "object" == typeof i && i, p(this).data())), t.data("tabsX", e)), "string" == typeof i && o.push(e[i].apply(e, n))
        }), o.length) {
            case 0:
                return this;
            case 1:
                return o[0];
            default:
                return o
        }
    }, p.fn.tabsX.defaults = {
        enableCache: !0,
        cacheTimeout: 3e5,
        maxTitleLength: 9,
        ajaxSettings: {},
        successCallback: {},
        errorCallback: {},
        addCss: "tabs-krajee"
    }, p.fn.tabsX.Constructor = s, p(document).on("ready", function () {
        p(".tabs-x").tabsX({})
    })
}), "function" != typeof Object.create && (Object.create = function (t) {
    function e() {
    }

    return e.prototype = t, new e
}), function (c, n) {
    var i = {
        init: function (t, e) {
            var i, n, o = this;
            o.elem = e, o.$elem = c(e), o.options = c.extend({}, c.fn.ezPlus.options, o.responsiveConfig(t || {})), o.imageSrc = o.$elem.data(o.options.attrImageZoomSrc) ? o.$elem.data(o.options.attrImageZoomSrc) : o.$elem.attr("src"), o.options.enabled && (o.options.tint && (o.options.lensColour = "none", o.options.lensOpacity = "1"), "inner" === o.options.zoomType && (o.options.showLens = !1), -1 === o.options.zoomId && (o.options.zoomId = (n = (new Date).getTime(), "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (t) {
                var e = (n + 16 * Math.random()) % 16 | 0;
                return n = Math.floor(n / 16), ("x" == t ? e : 3 & e | 8).toString(16)
            }))), o.$elem.parent().removeAttr("title").removeAttr("alt"), o.zoomImage = o.imageSrc, o.refresh(1), (i = c(o.options.gallery ? "#" + o.options.gallery : o.options.gallerySelector)).on("click.zoom", o.options.galleryItem, function (t) {
                if (o.options.galleryActiveClass && (c(o.options.galleryItem, i).removeClass(o.options.galleryActiveClass), c(this).addClass(o.options.galleryActiveClass)), "A" === this.tagName && t.preventDefault(), c(this).data(o.options.attrImageZoomSrc) ? o.zoomImagePre = c(this).data(o.options.attrImageZoomSrc) : o.zoomImagePre = c(this).data("image"), o.swaptheimage(c(this).data("image"), o.zoomImagePre), "A" === this.tagName) return !1
            }))
        }, refresh: function (t) {
            var e = this;
            setTimeout(function () {
                e.fetch(e.imageSrc)
            }, t || e.options.refresh)
        }, fetch: function (t) {
            var e = this, i = new Image;
            i.onload = function () {
                e.largeWidth = i.width, e.largeHeight = i.height, e.startZoom(), e.currentImage = e.imageSrc, e.options.onZoomedImageLoaded(e.$elem)
            }, e.setImageSource(i, t)
        }, setImageSource: function (t, e) {
            t.src = e
        }, startZoom: function () {
            var i = this;
            i.nzWidth = i.$elem.width(), i.nzHeight = i.$elem.height(), i.isWindowActive = !1, i.isLensActive = !1, i.isTintActive = !1, i.overWindow = !1, i.options.imageCrossfade && (i.zoomWrap = i.$elem.wrap('<div style="height:' + i.nzHeight + "px;width:" + i.nzWidth + 'px;" class="zoomWrapper" />'), i.$elem.css("position", "absolute")), i.zoomLock = 1, i.scrollingLock = !1, i.changeBgSize = !1, i.currentZoomLevel = i.options.zoomLevel, i.nzOffset = i.$elem.offset(), i.widthRatio = i.largeWidth / i.currentZoomLevel / i.nzWidth, i.heightRatio = i.largeHeight / i.currentZoomLevel / i.nzHeight, "window" === i.options.zoomType && (i.zoomWindowStyle = "overflow: hidden;background-position: 0px 0px;text-align:center;background-color: " + String(i.options.zoomWindowBgColour) + ";width: " + String(i.options.zoomWindowWidth) + "px;height: " + String(i.options.zoomWindowHeight) + "px;float: left;background-size: " + i.largeWidth / i.currentZoomLevel + "px " + i.largeHeight / i.currentZoomLevel + "px;display: none;z-index:100;border: " + String(i.options.borderSize) + "px solid " + i.options.borderColour + ";background-repeat: no-repeat;position: absolute;"), "inner" === i.options.zoomType && (i.zoomWindowStyle = (t = i.$elem.css("border-left-width"), "overflow: hidden;margin-left: " + String(t) + ";margin-top: " + String(t) + ";background-position: 0px 0px;width: " + String(i.nzWidth) + "px;height: " + String(i.nzHeight) + "px;float: left;display: none;cursor:" + i.options.cursor + ";border: " + String(i.options.borderSize) + "px solid " + i.options.borderColour + ";background-repeat: no-repeat;position: absolute;")), "window" === i.options.zoomType && (i.lensStyle = (i.nzHeight < i.options.zoomWindowHeight / i.heightRatio ? i.lensHeight = i.nzHeight : i.lensHeight = String(i.options.zoomWindowHeight / i.heightRatio), i.largeWidth < i.options.zoomWindowWidth ? i.lensWidth = i.nzWidth : i.lensWidth = String(i.options.zoomWindowWidth / i.widthRatio), "background-position: 0px 0px;width: " + String(i.options.zoomWindowWidth / i.widthRatio) + "px;height: " + String(i.options.zoomWindowHeight / i.heightRatio) + "px;float: right;display: none;overflow: hidden;z-index: 999;opacity:" + i.options.lensOpacity + ";filter: alpha(opacity = " + 100 * i.options.lensOpacity + "); zoom:1;width:" + i.lensWidth + "px;height:" + i.lensHeight + "px;background-color:" + i.options.lensColour + ";cursor:" + i.options.cursor + ";border: " + i.options.lensBorderSize + "px solid " + i.options.lensBorderColour + ";background-repeat: no-repeat;position: absolute;")), i.tintStyle = "display: block;position: absolute;background-color: " + i.options.tintColour + ";filter:alpha(opacity=0);opacity: 0;width: " + i.nzWidth + "px;height: " + i.nzHeight + "px;", i.lensRound = "", "lens" === i.options.zoomType && (i.lensStyle = "background-position: 0px 0px;float: left;display: none;border: " + String(i.options.borderSize) + "px solid " + i.options.borderColour + ";width:" + String(i.options.lensSize) + "px;height:" + String(i.options.lensSize) + "px;background-repeat: no-repeat;position: absolute;"), "round" === i.options.lensShape && (i.lensRound = "border-top-left-radius: " + String(i.options.lensSize / 2 + i.options.borderSize) + "px;border-top-right-radius: " + String(i.options.lensSize / 2 + i.options.borderSize) + "px;border-bottom-left-radius: " + String(i.options.lensSize / 2 + i.options.borderSize) + "px;border-bottom-right-radius: " + String(i.options.lensSize / 2 + i.options.borderSize) + "px;"), i.zoomContainer = c('<div class="zoomContainer" uuid="' + i.options.zoomId + '"style="position:absolute;left:' + i.nzOffset.left + "px;top:" + i.nzOffset.top + "px;height:" + i.nzHeight + "px;width:" + i.nzWidth + "px;z-index:" + i.options.zIndex + '"></div>'), i.$elem.attr("id") && i.zoomContainer.attr("id", i.$elem.attr("id") + "-zoomContainer"), c(i.options.zoomContainerAppendTo).append(i.zoomContainer), i.options.containLensZoom && "lens" === i.options.zoomType && i.zoomContainer.css("overflow", "hidden"), "inner" !== i.options.zoomType && (i.zoomLens = c('<div class="zoomLens" style="' + i.lensStyle + i.lensRound + '">&nbsp;</div>').appendTo(i.zoomContainer).click(function () {
                i.$elem.trigger("click")
            }), i.options.tint && (i.tintContainer = c("<div/>").addClass("tintContainer"), i.zoomTint = c('<div class="zoomTint" style="' + i.tintStyle + '"></div>'), i.zoomLens.wrap(i.tintContainer), i.zoomTintcss = i.zoomLens.after(i.zoomTint), i.zoomTintImage = c('<img style="position: absolute; left: 0px; top: 0px; max-width: none; width: ' + i.nzWidth + "px; height: " + i.nzHeight + 'px;" src="' + i.imageSrc + '">').appendTo(i.zoomLens).click(function () {
                i.$elem.trigger("click")
            })));
            var t = isNaN(i.options.zoomWindowPosition) ? "body" : i.zoomContainer;

            function e(t) {
                i.lastX === t.clientX && i.lastY === t.clientY || (i.setPosition(t), i.currentLoc = t), i.lastX = t.clientX, i.lastY = t.clientY
            }

            i.zoomWindow = c('<div style="z-index:999;left:' + i.windowOffsetLeft + "px;top:" + i.windowOffsetTop + "px;" + i.zoomWindowStyle + '" class="zoomWindow">&nbsp;</div>').appendTo(t).click(function () {
                i.$elem.trigger("click")
            }), i.zoomWindowContainer = c("<div/>").addClass("zoomWindowContainer").css("width", i.options.zoomWindowWidth), i.zoomWindow.wrap(i.zoomWindowContainer), "lens" === i.options.zoomType && i.zoomLens.css("background-image", 'url("' + i.imageSrc + '")'), "window" === i.options.zoomType && i.zoomWindow.css("background-image", 'url("' + i.imageSrc + '")'), "inner" === i.options.zoomType && i.zoomWindow.css("background-image", 'url("' + i.imageSrc + '")'), i.options.touchEnabled && (i.$elem.bind("touchmove.ezpspace", function (t) {
                t.preventDefault();
                t = t.originalEvent.touches[0] || t.originalEvent.changedTouches[0];
                i.setPosition(t)
            }), i.zoomContainer.bind("touchmove.ezpspace", function (t) {
                "inner" === i.options.zoomType && i.showHideWindow("show"), t.preventDefault();
                t = t.originalEvent.touches[0] || t.originalEvent.changedTouches[0];
                i.setPosition(t)
            }), i.zoomContainer.bind("touchend.ezpspace", function (t) {
                i.showHideWindow("hide"), i.options.showLens && i.showHideLens("hide"), i.options.tint && "inner" !== i.options.zoomType && i.showHideTint("hide")
            }), i.$elem.bind("touchend.ezpspace", function (t) {
                i.showHideWindow("hide"), i.options.showLens && i.showHideLens("hide"), i.options.tint && "inner" !== i.options.zoomType && i.showHideTint("hide")
            }), i.options.showLens && (i.zoomLens.bind("touchmove.ezpspace", function (t) {
                t.preventDefault();
                t = t.originalEvent.touches[0] || t.originalEvent.changedTouches[0];
                i.setPosition(t)
            }), i.zoomLens.bind("touchend.ezpspace", function (t) {
                i.showHideWindow("hide"), i.options.showLens && i.showHideLens("hide"), i.options.tint && "inner" !== i.options.zoomType && i.showHideTint("hide")
            }))), i.$elem.bind("mousemove.ezpspace", function (t) {
                !1 === i.overWindow && i.setElements("show"), i.lastX === t.clientX && i.lastY === t.clientY || (i.setPosition(t), i.currentLoc = t), i.lastX = t.clientX, i.lastY = t.clientY
            }), i.zoomContainer.bind("click.ezpspace", i.options.onImageClick), i.zoomContainer.bind("mousemove.ezpspace", function (t) {
                !1 === i.overWindow && i.setElements("show"), e(t)
            });
            t = null;
            "inner" !== i.options.zoomType && (t = i.zoomLens), i.options.tint && "inner" !== i.options.zoomType && (t = i.zoomTint), (t = "inner" === i.options.zoomType ? i.zoomWindow : t) && t.bind("mousemove.ezpspace", e), i.zoomContainer.add(i.$elem).mouseenter(function () {
                !1 === i.overWindow && i.setElements("show")
            }).mouseleave(function () {
                i.scrollLock || (i.setElements("hide"), i.options.onDestroy(i.$elem))
            }), "inner" !== i.options.zoomType && i.zoomWindow.mouseenter(function () {
                i.overWindow = !0, i.setElements("hide")
            }).mouseleave(function () {
                i.overWindow = !1
            }), i.options.minZoomLevel ? i.minZoomLevel = i.options.minZoomLevel : i.minZoomLevel = 2 * i.options.scrollZoomIncrement, i.options.scrollZoom && i.zoomContainer.add(i.$elem).bind("wheel DOMMouseScroll MozMousePixelScroll", function (t) {
                i.scrollLock = !0, clearTimeout(c.data(this, "timer")), c.data(this, "timer", setTimeout(function () {
                    i.scrollLock = !1
                }, 250));
                var e = t.originalEvent.deltaY || -1 * t.originalEvent.detail;
                return t.stopImmediatePropagation(), t.stopPropagation(), t.preventDefault(), 0 < e / 120 ? i.currentZoomLevel >= i.minZoomLevel && i.changeZoomLevel(i.currentZoomLevel - i.options.scrollZoomIncrement) : (i.fullheight || i.fullwidth) && i.options.mantainZoomAspectRatio || (!i.options.maxZoomLevel || i.currentZoomLevel <= i.options.maxZoomLevel) && i.changeZoomLevel(parseFloat(i.currentZoomLevel) + i.options.scrollZoomIncrement), !1
            })
        }, destroy: function () {
            this.$elem.unbind("ezpspace"), c(this.zoomContainer).remove(), this.options.loadingIcon && this.spinner && this.spinner.length && (this.spinner.remove(), delete this.spinner)
        }, getIdentifier: function () {
            return this.options.zoomId
        }, setElements: function (t) {
            var e = this;
            if (!e.options.zoomEnabled) return !1;
            "show" === t && e.isWindowSet && ("inner" === e.options.zoomType && e.showHideWindow("show"), "window" === e.options.zoomType && e.showHideWindow("show"), e.options.showLens && e.showHideLens("show"), e.options.tint && "inner" !== e.options.zoomType && e.showHideTint("show")), "hide" === t && ("window" === e.options.zoomType && e.showHideWindow("hide"), e.options.tint || e.showHideWindow("hide"), e.options.showLens && e.showHideLens("hide"), e.options.tint && e.showHideTint("hide"))
        }, setPosition: function (t) {
            var e, i, n = this;
            if (!n.options.zoomEnabled) return !1;
            n.nzHeight = n.$elem.height(), n.nzWidth = n.$elem.width(), n.nzOffset = n.$elem.offset(), n.options.tint && "inner" !== n.options.zoomType && n.zoomTint.css({
                top: 0,
                left: 0
            }), n.options.responsive && !n.options.scrollZoom && n.options.showLens && (n.nzHeight < n.options.zoomWindowWidth / n.widthRatio ? n.lensHeight = n.nzHeight : n.lensHeight = String(n.options.zoomWindowHeight / n.heightRatio), n.largeWidth < n.options.zoomWindowWidth ? n.lensWidth = n.nzWidth : n.lensWidth = n.options.zoomWindowWidth / n.widthRatio, n.widthRatio = n.largeWidth / n.nzWidth, n.heightRatio = n.largeHeight / n.nzHeight, "lens" !== n.options.zoomType && (n.nzHeight < n.options.zoomWindowWidth / n.widthRatio ? n.lensHeight = n.nzHeight : n.lensHeight = String(n.options.zoomWindowHeight / n.heightRatio), n.nzWidth < n.options.zoomWindowHeight / n.heightRatio ? n.lensWidth = n.nzWidth : n.lensWidth = String(n.options.zoomWindowWidth / n.widthRatio), n.zoomLens.css({
                width: n.lensWidth,
                height: n.lensHeight
            }), n.options.tint && n.zoomTintImage.css({
                width: n.nzWidth,
                height: n.nzHeight
            })), "lens" === n.options.zoomType && n.zoomLens.css({
                width: String(n.options.lensSize) + "px",
                height: String(n.options.lensSize) + "px"
            })), n.zoomContainer.css({
                top: n.nzOffset.top,
                left: n.nzOffset.left,
                width: n.nzWidth,
                height: n.nzHeight
            }), n.mouseLeft = parseInt(t.pageX - n.nzOffset.left), n.mouseTop = parseInt(t.pageY - n.nzOffset.top), "window" === n.options.zoomType && (e = n.zoomLens.height() / 2, i = n.zoomLens.width() / 2, n.Etoppos = n.mouseTop < 0 + e, n.Eboppos = n.mouseTop > n.nzHeight - e - 2 * n.options.lensBorderSize, n.Eloppos = n.mouseLeft < 0 + i, n.Eroppos = n.mouseLeft > n.nzWidth - i - 2 * n.options.lensBorderSize), "inner" === n.options.zoomType && (n.Etoppos = n.mouseTop < n.nzHeight / 2 / n.heightRatio, n.Eboppos = n.mouseTop > n.nzHeight - n.nzHeight / 2 / n.heightRatio, n.Eloppos = n.mouseLeft < 0 + n.nzWidth / 2 / n.widthRatio, n.Eroppos = n.mouseLeft > n.nzWidth - n.nzWidth / 2 / n.widthRatio - 2 * n.options.lensBorderSize), n.mouseLeft < 0 || n.mouseTop < 0 || n.mouseLeft > n.nzWidth || n.mouseTop > n.nzHeight ? n.setElements("hide") : (n.options.showLens && (n.lensLeftPos = String(Math.floor(n.mouseLeft - n.zoomLens.width() / 2)), n.lensTopPos = String(Math.floor(n.mouseTop - n.zoomLens.height() / 2))), n.Etoppos && (n.lensTopPos = 0), n.Eloppos && (n.windowLeftPos = 0, n.lensLeftPos = 0, n.tintpos = 0), "window" === n.options.zoomType && (n.Eboppos && (n.lensTopPos = Math.max(n.nzHeight - n.zoomLens.height() - 2 * n.options.lensBorderSize, 0)), n.Eroppos && (n.lensLeftPos = n.nzWidth - n.zoomLens.width() - 2 * n.options.lensBorderSize)), "inner" === n.options.zoomType && (n.Eboppos && (n.lensTopPos = Math.max(n.nzHeight - 2 * n.options.lensBorderSize, 0)), n.Eroppos && (n.lensLeftPos = n.nzWidth - n.nzWidth - 2 * n.options.lensBorderSize)), "lens" === n.options.zoomType && (n.windowLeftPos = String(-1 * ((t.pageX - n.nzOffset.left) * n.widthRatio - n.zoomLens.width() / 2)), n.windowTopPos = String(-1 * ((t.pageY - n.nzOffset.top) * n.heightRatio - n.zoomLens.height() / 2)), n.zoomLens.css("background-position", n.windowLeftPos + "px " + n.windowTopPos + "px"), n.changeBgSize && (n.nzHeight > n.nzWidth ? ("lens" === n.options.zoomType && n.zoomLens.css("background-size", n.largeWidth / n.newvalueheight + "px " + n.largeHeight / n.newvalueheight + "px"), n.zoomWindow.css("background-size", n.largeWidth / n.newvalueheight + "px " + n.largeHeight / n.newvalueheight + "px")) : ("lens" === n.options.zoomType && n.zoomLens.css("background-size", n.largeWidth / n.newvaluewidth + "px " + n.largeHeight / n.newvaluewidth + "px"), n.zoomWindow.css("background-size", n.largeWidth / n.newvaluewidth + "px " + n.largeHeight / n.newvaluewidth + "px")), n.changeBgSize = !1), n.setWindowPosition(t)), n.options.tint && "inner" !== n.options.zoomType && n.setTintPosition(t), "window" === n.options.zoomType && n.setWindowPosition(t), "inner" === n.options.zoomType && n.setWindowPosition(t), n.options.showLens && (n.fullwidth && "lens" !== n.options.zoomType && (n.lensLeftPos = 0), n.zoomLens.css({
                left: n.lensLeftPos + "px",
                top: n.lensTopPos + "px"
            })))
        }, showHideZoomContainer: function (t) {
            "show" === t && this.zoomContainer && this.zoomContainer.show(), "hide" === t && this.zoomContainer && this.zoomContainer.hide()
        }, showHideWindow: function (t) {
            var e = this;
            "show" === t && !e.isWindowActive && e.zoomWindow && (e.options.onShow(e), e.options.zoomWindowFadeIn ? e.zoomWindow.stop(!0, !0, !1).fadeIn(e.options.zoomWindowFadeIn) : e.zoomWindow.show(), e.isWindowActive = !0), "hide" === t && e.isWindowActive && (e.options.zoomWindowFadeOut ? e.zoomWindow.stop(!0, !0).fadeOut(e.options.zoomWindowFadeOut, function () {
                e.loop && (clearInterval(e.loop), e.loop = !1)
            }) : e.zoomWindow.hide(), e.isWindowActive = !1)
        }, showHideLens: function (t) {
            var e = this;
            "show" === t && (e.isLensActive || (e.options.lensFadeIn && e.zoomLens ? e.zoomLens.stop(!0, !0, !1).fadeIn(e.options.lensFadeIn) : e.zoomLens.show(), e.isLensActive = !0)), "hide" === t && e.isLensActive && (e.options.lensFadeOut ? e.zoomLens.stop(!0, !0).fadeOut(e.options.lensFadeOut) : e.zoomLens.hide(), e.isLensActive = !1)
        }, showHideTint: function (t) {
            var e = this;
            "show" === t && !e.isTintActive && e.zoomTint && (e.options.zoomTintFadeIn ? e.zoomTint.css("opacity", e.options.tintOpacity).animate().stop(!0, !0).fadeIn("slow") : (e.zoomTint.css("opacity", e.options.tintOpacity).animate(), e.zoomTint.show()), e.isTintActive = !0), "hide" === t && e.isTintActive && (e.options.zoomTintFadeOut ? e.zoomTint.stop(!0, !0).fadeOut(e.options.zoomTintFadeOut) : e.zoomTint.hide(), e.isTintActive = !1)
        }, setLensPosition: function (t) {
        }, setWindowPosition: function (t) {
            var e, i = this;
            if (isNaN(i.options.zoomWindowPosition)) i.externalContainer = c(i.options.zoomWindowPosition), i.externalContainer.length || (i.externalContainer = c("#" + i.options.zoomWindowPosition)), i.externalContainerWidth = i.externalContainer.width(), i.externalContainerHeight = i.externalContainer.height(), i.externalContainerOffset = i.externalContainer.offset(), i.windowOffsetTop = i.externalContainerOffset.top, i.windowOffsetLeft = i.externalContainerOffset.left; else switch (i.options.zoomWindowPosition) {
                case 1:
                    i.windowOffsetTop = i.options.zoomWindowOffsetY, i.windowOffsetLeft = +i.nzWidth;
                    break;
                case 2:
                    i.options.zoomWindowHeight > i.nzHeight ? (i.windowOffsetTop = -1 * (i.options.zoomWindowHeight / 2 - i.nzHeight / 2), i.windowOffsetLeft = i.nzWidth) : c.noop();
                    break;
                case 3:
                    i.windowOffsetTop = i.nzHeight - i.zoomWindow.height() - 2 * i.options.borderSize, i.windowOffsetLeft = i.nzWidth;
                    break;
                case 4:
                    i.windowOffsetTop = i.nzHeight, i.windowOffsetLeft = i.nzWidth;
                    break;
                case 5:
                    i.windowOffsetTop = i.nzHeight, i.windowOffsetLeft = i.nzWidth - i.zoomWindow.width() - 2 * i.options.borderSize;
                    break;
                case 6:
                    i.options.zoomWindowHeight > i.nzHeight ? (i.windowOffsetTop = i.nzHeight, i.windowOffsetLeft = -1 * (i.options.zoomWindowWidth / 2 - i.nzWidth / 2 + 2 * i.options.borderSize)) : c.noop();
                    break;
                case 7:
                    i.windowOffsetTop = i.nzHeight, i.windowOffsetLeft = 0;
                    break;
                case 8:
                    i.windowOffsetTop = i.nzHeight, i.windowOffsetLeft = -1 * (i.zoomWindow.width() + 2 * i.options.borderSize);
                    break;
                case 9:
                    i.windowOffsetTop = i.nzHeight - i.zoomWindow.height() - 2 * i.options.borderSize, i.windowOffsetLeft = -1 * (i.zoomWindow.width() + 2 * i.options.borderSize);
                    break;
                case 10:
                    i.options.zoomWindowHeight > i.nzHeight ? (i.windowOffsetTop = -1 * (i.options.zoomWindowHeight / 2 - i.nzHeight / 2), i.windowOffsetLeft = -1 * (i.zoomWindow.width() + 2 * i.options.borderSize)) : c.noop();
                    break;
                case 11:
                    i.windowOffsetTop = i.options.zoomWindowOffsetY, i.windowOffsetLeft = -1 * (i.zoomWindow.width() + 2 * i.options.borderSize);
                    break;
                case 12:
                    i.windowOffsetTop = -1 * (i.zoomWindow.height() + 2 * i.options.borderSize), i.windowOffsetLeft = -1 * (i.zoomWindow.width() + 2 * i.options.borderSize);
                    break;
                case 13:
                    i.windowOffsetTop = -1 * (i.zoomWindow.height() + 2 * i.options.borderSize), i.windowOffsetLeft = 0;
                    break;
                case 14:
                    i.options.zoomWindowHeight > i.nzHeight ? (i.windowOffsetTop = -1 * (i.zoomWindow.height() + 2 * i.options.borderSize), i.windowOffsetLeft = -1 * (i.options.zoomWindowWidth / 2 - i.nzWidth / 2 + 2 * i.options.borderSize)) : c.noop();
                    break;
                case 15:
                    i.windowOffsetTop = -1 * (i.zoomWindow.height() + 2 * i.options.borderSize), i.windowOffsetLeft = i.nzWidth - i.zoomWindow.width() - 2 * i.options.borderSize;
                    break;
                case 16:
                    i.windowOffsetTop = -1 * (i.zoomWindow.height() + 2 * i.options.borderSize), i.windowOffsetLeft = i.nzWidth;
                    break;
                default:
                    i.windowOffsetTop = i.options.zoomWindowOffsetY, i.windowOffsetLeft = i.nzWidth
            }
            i.isWindowSet = !0, i.windowOffsetTop = i.windowOffsetTop + i.options.zoomWindowOffsetY, i.windowOffsetLeft = i.windowOffsetLeft + i.options.zoomWindowOffsetX, i.zoomWindow.css({
                top: i.windowOffsetTop,
                left: i.windowOffsetLeft
            }), "inner" === i.options.zoomType && i.zoomWindow.css({
                top: 0,
                left: 0
            }), i.windowLeftPos = String(-1 * ((t.pageX - i.nzOffset.left) * i.widthRatio - i.zoomWindow.width() / 2)), i.windowTopPos = String(-1 * ((t.pageY - i.nzOffset.top) * i.heightRatio - i.zoomWindow.height() / 2)), i.Etoppos && (i.windowTopPos = 0), i.Eloppos && (i.windowLeftPos = 0), i.Eboppos && (i.windowTopPos = -1 * (i.largeHeight / i.currentZoomLevel - i.zoomWindow.height())), i.Eroppos && (i.windowLeftPos = -1 * (i.largeWidth / i.currentZoomLevel - i.zoomWindow.width())), i.fullheight && (i.windowTopPos = 0), i.fullwidth && (i.windowLeftPos = 0), "window" !== i.options.zoomType && "inner" !== i.options.zoomType || (1 === i.zoomLock && (i.widthRatio <= 1 && (i.windowLeftPos = 0), i.heightRatio <= 1 && (i.windowTopPos = 0)), "window" === i.options.zoomType && (i.largeHeight < i.options.zoomWindowHeight && (i.windowTopPos = 0), i.largeWidth < i.options.zoomWindowWidth && (i.windowLeftPos = 0)), i.options.easing ? (i.xp || (i.xp = 0), i.yp || (i.yp = 0), e = 16, Number.isInteger(parseInt(i.options.easing)) && (e = parseInt(i.options.easing)), i.loop || (i.loop = setInterval(function () {
                i.xp += (i.windowLeftPos - i.xp) / i.options.easingAmount, i.yp += (i.windowTopPos - i.yp) / i.options.easingAmount, i.scrollingLock ? (clearInterval(i.loop), i.xp = i.windowLeftPos, i.yp = i.windowTopPos, i.xp = -1 * ((t.pageX - i.nzOffset.left) * i.widthRatio - i.zoomWindow.width() / 2), i.yp = -1 * ((t.pageY - i.nzOffset.top) * i.heightRatio - i.zoomWindow.height() / 2), i.changeBgSize && (i.nzHeight > i.nzWidth ? ("lens" === i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px"), i.zoomWindow.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px")) : ("lens" !== i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvalueheight + "px"), i.zoomWindow.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvaluewidth + "px")), i.changeBgSize = !1), i.zoomWindow.css("background-position", i.windowLeftPos + "px " + i.windowTopPos + "px"), i.scrollingLock = !1, i.loop = !1) : Math.round(Math.abs(i.xp - i.windowLeftPos) + Math.abs(i.yp - i.windowTopPos)) < 1 ? (clearInterval(i.loop), i.zoomWindow.css("background-position", i.windowLeftPos + "px " + i.windowTopPos + "px"), i.loop = !1) : (i.changeBgSize && (i.nzHeight > i.nzWidth ? ("lens" === i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px"), i.zoomWindow.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px")) : ("lens" !== i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvaluewidth + "px"), i.zoomWindow.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvaluewidth + "px")), i.changeBgSize = !1), i.zoomWindow.css("background-position", i.xp + "px " + i.yp + "px"))
            }, e))) : (i.changeBgSize && (i.nzHeight > i.nzWidth ? ("lens" === i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px"), i.zoomWindow.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px")) : ("lens" === i.options.zoomType && i.zoomLens.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvaluewidth + "px"), i.largeHeight / i.newvaluewidth < i.options.zoomWindowHeight ? i.zoomWindow.css("background-size", i.largeWidth / i.newvaluewidth + "px " + i.largeHeight / i.newvaluewidth + "px") : i.zoomWindow.css("background-size", i.largeWidth / i.newvalueheight + "px " + i.largeHeight / i.newvalueheight + "px")), i.changeBgSize = !1), i.zoomWindow.css("background-position", i.windowLeftPos + "px " + i.windowTopPos + "px")))
        }, setTintPosition: function (t) {
            var e = this, i = e.zoomLens.width(), n = e.zoomLens.height();
            e.nzOffset = e.$elem.offset(), e.tintpos = String(-1 * (t.pageX - e.nzOffset.left - i / 2)), e.tintposy = String(-1 * (t.pageY - e.nzOffset.top - n / 2)), e.Etoppos && (e.tintposy = 0), e.Eloppos && (e.tintpos = 0), e.Eboppos && (e.tintposy = -1 * (e.nzHeight - n - 2 * e.options.lensBorderSize)), e.Eroppos && (e.tintpos = -1 * (e.nzWidth - i - 2 * e.options.lensBorderSize)), e.options.tint && (e.fullheight && (e.tintposy = 0), e.fullwidth && (e.tintpos = 0), e.zoomTintImage.css({
                left: e.tintpos + "px",
                top: e.tintposy + "px"
            }))
        }, swaptheimage: function (t, e) {
            var i, n = this, o = new Image;
            n.options.loadingIcon && !n.spinner ? (i = "background: url('" + n.options.loadingIcon + "') no-repeat center;height:" + n.nzHeight + "px;width:" + n.nzWidth + "px;z-index: 2000;position: absolute; background-position: center center;", "inner" === n.options.zoomType && (i += "top: 0px;"), n.spinner = c('<div class="ezp-spinner" style="' + i + '"></div>'), n.$elem.after(n.spinner)) : n.spinner && n.spinner.show(), n.options.onImageSwap(n.$elem), o.onload = function () {
                n.largeWidth = o.width, n.largeHeight = o.height, n.zoomImage = e, n.zoomWindow.css("background-size", n.largeWidth + "px " + n.largeHeight + "px"), n.swapAction(t, e)
            }, n.setImageSource(o, e)
        }, swapAction: function (t, e) {
            var i, n, o, s = this, a = s.$elem.width(), r = s.$elem.height(), l = new Image;
            l.onload = function () {
                s.nzHeight = l.height, s.nzWidth = l.width, s.options.onImageSwapComplete(s.$elem), s.doneCallback()
            }, s.setImageSource(l, t), s.currentZoomLevel = s.options.zoomLevel, s.options.maxZoomLevel = !1, "lens" === s.options.zoomType && s.zoomLens.css("background-image", 'url("' + e + '")'), "window" === s.options.zoomType && s.zoomWindow.css("background-image", 'url("' + e + '")'), "inner" === s.options.zoomType && s.zoomWindow.css("background-image", 'url("' + e + '")'), s.currentImage = e, s.options.imageCrossfade ? (i = (n = s.$elem).clone(), s.$elem.attr("src", t), s.$elem.after(i), i.stop(!0).fadeOut(s.options.imageCrossfade, function () {
                c(this).remove()
            }), s.$elem.width("auto").removeAttr("width"), s.$elem.height("auto").removeAttr("height"), n.fadeIn(s.options.imageCrossfade), s.options.tint && "inner" !== s.options.zoomType && (n = (i = s.zoomTintImage).clone(), s.zoomTintImage.attr("src", e), s.zoomTintImage.after(n), n.stop(!0).fadeOut(s.options.imageCrossfade, function () {
                c(this).remove()
            }), i.fadeIn(s.options.imageCrossfade), s.zoomTint.css({
                height: r,
                width: a
            })), s.zoomContainer.css({
                height: r,
                width: a
            }), "inner" === s.options.zoomType && (s.options.constrainType || (s.zoomWrap.parent().css({
                height: r,
                width: a
            }), s.zoomWindow.css({
                height: r,
                width: a
            })))) : (s.$elem.attr("src", t), s.options.tint && (s.zoomTintImage.attr("src", e), s.zoomTintImage.attr("height", r), s.zoomTintImage.css("height", r), s.zoomTint.css("height", r)), s.zoomContainer.css({
                height: r,
                width: a
            })), s.options.imageCrossfade && s.zoomWrap.css({
                height: r,
                width: a
            }), s.options.constrainType && ("height" === s.options.constrainType && (o = {
                height: s.options.constrainSize,
                width: "auto"
            }, s.zoomContainer.css(o), s.options.imageCrossfade ? (s.zoomWrap.css(o), s.constwidth = s.zoomWrap.width()) : (s.$elem.css(o), s.constwidth = a), o = {
                height: s.options.constrainSize,
                width: s.constwidth
            }, "inner" === s.options.zoomType && (s.zoomWrap.parent().css(o), s.zoomWindow.css(o)), s.options.tint && (s.tintContainer.css(o), s.zoomTint.css(o), s.zoomTintImage.css(o))), "width" === s.options.constrainType && (o = {
                height: "auto",
                width: s.options.constrainSize
            }, s.zoomContainer.css(o), s.options.imageCrossfade ? (s.zoomWrap.css(o), s.constheight = s.zoomWrap.height()) : (s.$elem.css(o), s.constheight = r), r = {
                height: s.constheight,
                width: s.options.constrainSize
            }, "inner" === s.options.zoomType && (s.zoomWrap.parent().css(r), s.zoomWindow.css(r)), s.options.tint && (s.tintContainer.css(r), s.zoomTint.css(r), s.zoomTintImage.css(r))))
        }, doneCallback: function () {
            var t = this;
            t.options.loadingIcon && t.spinner && t.spinner.length && t.spinner.hide(), t.nzOffset = t.$elem.offset(), t.nzWidth = t.$elem.width(), t.nzHeight = t.$elem.height(), t.currentZoomLevel = t.options.zoomLevel, t.widthRatio = t.largeWidth / t.nzWidth, t.heightRatio = t.largeHeight / t.nzHeight, "window" === t.options.zoomType && (t.nzHeight < t.options.zoomWindowHeight / t.heightRatio ? t.lensHeight = t.nzHeight : t.lensHeight = String(t.options.zoomWindowHeight / t.heightRatio), t.nzWidth < t.options.zoomWindowWidth ? t.lensWidth = t.nzWidth : t.lensWidth = t.options.zoomWindowWidth / t.widthRatio, t.zoomLens && t.zoomLens.css({
                width: t.lensWidth,
                height: t.lensHeight
            }))
        }, getCurrentImage: function () {
            return this.zoomImage
        }, getGalleryList: function () {
            var e = this;
            return e.gallerylist = [], e.options.gallery ? c("#" + e.options.gallery + " a").each(function () {
                var t = "";
                c(this).data(e.options.attrImageZoomSrc) ? t = c(this).data(e.options.attrImageZoomSrc) : c(this).data("image") && (t = c(this).data("image")), t === e.zoomImage ? e.gallerylist.unshift({
                    href: "" + t,
                    title: c(this).find("img").attr("title")
                }) : e.gallerylist.push({href: "" + t, title: c(this).find("img").attr("title")})
            }) : e.gallerylist.push({href: "" + e.zoomImage, title: c(this).find("img").attr("title")}), e.gallerylist
        }, changeZoomLevel: function (t) {
            var e = this;
            e.scrollingLock = !0, e.newvalue = parseFloat(t).toFixed(2);
            var i = e.newvalue, n = e.largeHeight / (e.options.zoomWindowHeight / e.nzHeight * e.nzHeight),
                t = e.largeWidth / (e.options.zoomWindowWidth / e.nzWidth * e.nzWidth);
            "inner" !== e.options.zoomType && (n <= i ? (e.heightRatio = e.largeHeight / n / e.nzHeight, e.newvalueheight = n, e.fullheight = !0) : (e.heightRatio = e.largeHeight / i / e.nzHeight, e.newvalueheight = i, e.fullheight = !1), t <= i ? (e.widthRatio = e.largeWidth / t / e.nzWidth, e.newvaluewidth = t, e.fullwidth = !0) : (e.widthRatio = e.largeWidth / i / e.nzWidth, e.newvaluewidth = i, e.fullwidth = !1), "lens" === e.options.zoomType && (n <= i ? (e.fullwidth = !0, e.newvaluewidth = n) : (e.widthRatio = e.largeWidth / i / e.nzWidth, e.newvaluewidth = i, e.fullwidth = !1))), "inner" === e.options.zoomType && ((n = parseFloat(e.largeHeight / e.nzHeight).toFixed(2)) <= (i = (t = parseFloat(e.largeWidth / e.nzWidth).toFixed(2)) < (i = n < i ? n : i) ? t : i) ? (e.heightRatio = e.largeHeight / i / e.nzHeight, e.newvalueheight = n < i ? n : i, e.fullheight = !0) : (e.heightRatio = e.largeHeight / i / e.nzHeight, e.newvalueheight = n < i ? n : i, e.fullheight = !1), t <= i ? (e.widthRatio = e.largeWidth / i / e.nzWidth, e.newvaluewidth = t < i ? t : i, e.fullwidth = !0) : (e.widthRatio = e.largeWidth / i / e.nzWidth, e.newvaluewidth = i, e.fullwidth = !1));
            i = !1;
            "inner" === e.options.zoomType && (e.nzWidth >= e.nzHeight && (e.newvaluewidth <= t ? i = !0 : (e.fullheight = !(i = !1), e.fullwidth = !0)), e.nzHeight > e.nzWidth && (e.newvaluewidth <= t ? i = !0 : (e.fullheight = !(i = !1), e.fullwidth = !0))), (i = "inner" !== e.options.zoomType ? !0 : i) && (e.zoomLock = 0, e.changeZoom = !0, e.options.zoomWindowHeight / e.heightRatio <= e.nzHeight && (e.currentZoomLevel = e.newvalueheight, "lens" !== e.options.zoomType && "inner" !== e.options.zoomType && (e.changeBgSize = !0, e.zoomLens.css("height", String(e.options.zoomWindowHeight / e.heightRatio) + "px")), "lens" !== e.options.zoomType && "inner" !== e.options.zoomType || (e.changeBgSize = !0)), e.options.zoomWindowWidth / e.widthRatio <= e.nzWidth && ("inner" !== e.options.zoomType && e.newvaluewidth > e.newvalueheight && (e.currentZoomLevel = e.newvaluewidth), "lens" !== e.options.zoomType && "inner" !== e.options.zoomType && (e.changeBgSize = !0, e.zoomLens.css("width", String(e.options.zoomWindowWidth / e.widthRatio) + "px")), "lens" !== e.options.zoomType && "inner" !== e.options.zoomType || (e.changeBgSize = !0)), "inner" === e.options.zoomType && (e.changeBgSize = !0, (e.nzWidth > e.nzHeight || e.nzHeight >= e.nzWidth) && (e.currentZoomLevel = e.newvaluewidth))), e.setPosition(e.currentLoc)
        }, closeAll: function () {
            this.zoomWindow && this.zoomWindow.hide(), this.zoomLens && this.zoomLens.hide(), this.zoomTint && this.zoomTint.hide()
        }, changeState: function (t) {
            "enable" === t && (this.options.zoomEnabled = !0), "disable" === t && (this.options.zoomEnabled = !1)
        }, responsiveConfig: function (t) {
            return t.respond && 0 < t.respond.length ? c.extend({}, t, this.configByScreenWidth(t)) : t
        }, configByScreenWidth: function (t) {
            var e = c(n).width(), i = c.grep(t.respond, function (t) {
                t = t.range.split("-");
                return e >= t[0] && e <= t[1]
            });
            return 0 < i.length ? i[0] : t
        }
    };
    c.fn.ezPlus = function (e) {
        return this.each(function () {
            var t = Object.create(i);
            t.init(e, this), c.data(this, "ezPlus", t)
        })
    }, c.fn.ezPlus.options = {
        attrImageZoomSrc: "zoom-image",
        borderColour: "#888",
        borderSize: 4,
        constrainSize: !1,
        constrainType: !1,
        containLensZoom: !1,
        cursor: "inherit",
        debug: !1,
        easing: !1,
        easingAmount: 12,
        enabled: !0,
        gallery: !1,
        galleryActiveClass: "zoomGalleryActive",
        gallerySelector: !1,
        galleryItem: "a",
        imageCrossfade: !1,
        lensBorderColour: "#000",
        lensBorderSize: 1,
        lensColour: "white",
        lensFadeIn: !1,
        lensFadeOut: !1,
        lensOpacity: .4,
        lensShape: "square",
        lensSize: 200,
        lenszoom: !1,
        loadingIcon: !1,
        mantainZoomAspectRatio: !1,
        maxZoomLevel: !1,
        minZoomLevel: !1,
        onComplete: c.noop,
        onDestroy: c.noop,
        onImageClick: c.noop,
        onImageSwap: c.noop,
        onImageSwapComplete: c.noop,
        onShow: c.noop,
        onZoomedImageLoaded: c.noop,
        preloading: 1,
        respond: [],
        responsive: !0,
        scrollZoom: !1,
        scrollZoomIncrement: .1,
        showLens: !0,
        tint: !1,
        tintColour: "#333",
        tintOpacity: .4,
        touchEnabled: !0,
        zoomActivation: "hover",
        zoomContainerAppendTo: "body",
        zoomId: -1,
        zoomLevel: 1,
        zoomTintFadeIn: !1,
        zoomTintFadeOut: !1,
        zoomType: "window",
        zoomWindowAlwaysShow: !1,
        zoomWindowBgColour: "#fff",
        zoomWindowFadeIn: !1,
        zoomWindowFadeOut: !1,
        zoomWindowHeight: 400,
        zoomWindowOffsetX: 0,
        zoomWindowOffsetY: 0,
        zoomWindowPosition: 1,
        zoomWindowWidth: 400,
        zoomEnabled: !0,
        zIndex: 999
    }
}(jQuery, window, document), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e(require("jquery")) : "function" == typeof define && define.amd ? define(["jquery"], e) : (t = t || self).BootstrapTable = e(t.jQuery)
}(this, function (N) {
    "use strict";
    N = N && Object.prototype.hasOwnProperty.call(N, "default") ? N.default : N;
    var t = "undefined" != typeof globalThis ? globalThis : "undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : {};

    function e(t, e) {
        return t(e = {exports: {}}, e.exports), e.exports
    }

    function i(t) {
        return t && t.Math == Math && t
    }

    function c(t) {
        try {
            return !!t()
        } catch (t) {
            return !0
        }
    }

    function g(t, e) {
        return {enumerable: !(1 & t), configurable: !(2 & t), writable: !(4 & t), value: e}
    }

    function d(t) {
        return x(b(t))
    }

    function n(t) {
        return C ? _.createElement(t) : {}
    }

    function u(e, i) {
        try {
            O(h, e, i)
        } catch (t) {
            h[e] = i
        }
        return i
    }

    var h = i("object" == typeof globalThis && globalThis) || i("object" == typeof window && window) || i("object" == typeof self && self) || i("object" == typeof t && t) || Function("return this")(),
        p = !c(function () {
            return 7 != Object.defineProperty({}, "a", {
                get: function () {
                    return 7
                }
            }).a
        }), o = {}.propertyIsEnumerable, s = Object.getOwnPropertyDescriptor, f = {
            f: s && !o.call({1: 2}, 1) ? function (t) {
                t = s(this, t);
                return !!t && t.enumerable
            } : o
        }, a = {}.toString, r = function (t) {
            return a.call(t).slice(8, -1)
        }, l = "".split, x = c(function () {
            return !Object("z").propertyIsEnumerable(0)
        }) ? function (t) {
            return "String" == r(t) ? l.call(t, "") : Object(t)
        } : Object, b = function (t) {
            if (null == t) throw TypeError("Can't call method on " + t);
            return t
        }, m = function (t) {
            return "object" == typeof t ? null !== t : "function" == typeof t
        }, v = function (t, e) {
            if (!m(t)) return t;
            var i, n;
            if (e && "function" == typeof (i = t.toString) && !m(n = i.call(t))) return n;
            if ("function" == typeof (i = t.valueOf) && !m(n = i.call(t))) return n;
            if (!e && "function" == typeof (i = t.toString) && !m(n = i.call(t))) return n;
            throw TypeError("Can't convert object to primitive value")
        }, y = {}.hasOwnProperty, w = function (t, e) {
            return y.call(t, e)
        }, _ = h.document, C = m(_) && m(_.createElement), S = !p && !c(function () {
            return 7 != Object.defineProperty(n("div"), "a", {
                get: function () {
                    return 7
                }
            }).a
        }), T = Object.getOwnPropertyDescriptor, $ = {
            f: p ? T : function (t, e) {
                if (t = d(t), e = v(e, !0), S) try {
                    return T(t, e)
                } catch (t) {
                }
                if (w(t, e)) return g(!f.f.call(t, e), t[e])
            }
        }, E = function (t) {
            if (!m(t)) throw TypeError(String(t) + " is not an object");
            return t
        }, k = Object.defineProperty, I = {
            f: p ? k : function (t, e, i) {
                if (E(t), e = v(e, !0), E(i), S) try {
                    return k(t, e, i)
                } catch (t) {
                }
                if ("get" in i || "set" in i) throw TypeError("Accessors not supported");
                return "value" in i && (t[e] = i.value), t
            }
        }, O = p ? function (t, e, i) {
            return I.f(t, e, g(1, i))
        } : function (t, e, i) {
            return t[e] = i, t
        }, z = "__core-js_shared__", A = h[z] || u(z, {}), P = Function.toString;
    "function" != typeof A.inspectSource && (A.inspectSource = function (t) {
        return P.call(t)
    });

    function D(t) {
        return "Symbol(" + String(void 0 === t ? "" : t) + ")_" + (++X + K).toString(36)
    }

    function L(t) {
        return Z[t] || (Z[t] = D(t))
    }

    var M, H, j, B, R, W, F, q, V = A.inspectSource, U = h.WeakMap,
        G = "function" == typeof U && /native code/.test(V(U)), Y = e(function (t) {
            (t.exports = function (t, e) {
                return A[t] || (A[t] = void 0 !== e ? e : {})
            })("versions", []).push({version: "3.6.0", mode: "global", copyright: "© 2019 Denis Pushkarev (zloirock.ru)"})
        }), X = 0, K = Math.random(), Z = Y("keys"), Q = {}, J = h.WeakMap;
    F = G ? (M = new J, H = M.get, j = M.has, B = M.set, R = function (t, e) {
        return B.call(M, t, e), e
    }, W = function (t) {
        return H.call(M, t) || {}
    }, function (t) {
        return j.call(M, t)
    }) : (q = L("state"), Q[q] = !0, R = function (t, e) {
        return O(t, q, e), e
    }, W = function (t) {
        return w(t, q) ? t[q] : {}
    }, function (t) {
        return w(t, q)
    });

    function tt(t) {
        return "function" == typeof t ? t : void 0
    }

    function et(t, e) {
        return arguments.length < 2 ? tt(mt[t]) || tt(h[t]) : mt[t] && mt[t][e] || h[t] && h[t][e]
    }

    function it(t) {
        return 0 < t ? yt(bt(t), 9007199254740991) : 0
    }

    function nt(t, e) {
        return (t = bt(t)) < 0 ? wt(t + e, 0) : xt(t, e)
    }

    function ot(r) {
        return function (t, e, i) {
            var n, o = d(t), s = it(o.length), a = nt(i, s);
            if (r && e != e) {
                for (; a < s;) if ((n = o[a++]) != n) return !0
            } else for (; a < s; a++) if ((r || a in o) && o[a] === e) return r || a || 0;
            return !r && -1
        }
    }

    function st(t, e) {
        var i, n = d(t), o = 0, s = [];
        for (i in n) !w(Q, i) && w(n, i) && s.push(i);
        for (; e.length > o;) w(n, i = e[o++]) && (~_t(s, i) || s.push(i));
        return s
    }

    function at(t, e) {
        for (var i = Et(e), n = I.f, o = $.f, s = 0; s < i.length; s++) {
            var a = i[s];
            w(t, a) || n(t, a, o(e, a))
        }
    }

    function rt(t, e) {
        return (t = Ot[It(t)]) == At || t != zt && ("function" == typeof e ? c(e) : !!e)
    }

    function lt(t, e) {
        var i, n, o, s, a = t.target, r = t.global, l = t.stat;
        if (i = r ? h : l ? h[a] || u(a, {}) : (h[a] || {}).prototype) for (n in e) {
            if (o = e[n], s = t.noTargetGet ? (s = Dt(i, n)) && s.value : i[n], !Pt(r ? n : a + (l ? "." : "#") + n, t.forced) && void 0 !== s) {
                if (typeof o == typeof s) continue;
                at(o, s)
            }
            (t.sham || s && s.sham) && O(o, "sham", !0), ft(i, n, o, t)
        }
    }

    function ct(t) {
        return Object(b(t))
    }

    function dt() {
    }

    function ut(t) {
        return "<script>" + t + "<\/script>"
    }

    var ht, pt = {
            set: R, get: W, has: F, enforce: function (t) {
                return F(t) ? W(t) : R(t, {})
            }, getterFor: function (i) {
                return function (t) {
                    var e;
                    if (!m(t) || (e = W(t)).type !== i) throw TypeError("Incompatible receiver, " + i + " required");
                    return e
                }
            }
        }, ft = e(function (t) {
            var e = pt.get, a = pt.enforce, r = String(String).split("String");
            (t.exports = function (t, e, i, n) {
                var o = !!n && !!n.unsafe, s = !!n && !!n.enumerable, n = !!n && !!n.noTargetGet;
                "function" == typeof i && ("string" != typeof e || w(i, "name") || O(i, "name", e), a(i).source = r.join("string" == typeof e ? e : "")), t !== h ? (o ? !n && t[e] && (s = !0) : delete t[e], s ? t[e] = i : O(t, e, i)) : s ? t[e] = i : u(e, i)
            })(Function.prototype, "toString", function () {
                return "function" == typeof this && e(this).source || V(this)
            })
        }), mt = h, gt = Math.ceil, vt = Math.floor, bt = function (t) {
            return isNaN(t = +t) ? 0 : (0 < t ? vt : gt)(t)
        }, yt = Math.min, wt = Math.max, xt = Math.min, t = {includes: ot(!0), indexOf: ot(!1)}, _t = t.indexOf,
        Ct = ["constructor", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "toLocaleString", "toString", "valueOf"],
        St = Ct.concat("length", "prototype"), Tt = {
            f: Object.getOwnPropertyNames || function (t) {
                return st(t, St)
            }
        }, $t = {f: Object.getOwnPropertySymbols}, Et = et("Reflect", "ownKeys") || function (t) {
            var e = Tt.f(E(t)), i = $t.f;
            return i ? e.concat(i(t)) : e
        }, kt = /#|\.prototype\./, It = rt.normalize = function (t) {
            return String(t).replace(kt, ".").toLowerCase()
        }, Ot = rt.data = {}, zt = rt.NATIVE = "N", At = rt.POLYFILL = "P", Pt = rt, Dt = $.f,
        Lt = !!Object.getOwnPropertySymbols && !c(function () {
            return !String(Symbol())
        }), o = Lt && !Symbol.sham && "symbol" == typeof Symbol(), Mt = Array.isArray || function (t) {
            return "Array" == r(t)
        }, Nt = Object.keys || function (t) {
            return st(t, Ct)
        }, Ht = p ? Object.defineProperties : function (t, e) {
            E(t);
            for (var i, n = Nt(e), o = n.length, s = 0; s < o;) I.f(t, i = n[s++], e[i]);
            return t
        }, jt = et("document", "documentElement"), Bt = L("IE_PROTO"), Rt = function () {
            try {
                ht = document.domain && new ActiveXObject("htmlfile")
            } catch (t) {
            }
            var t, e;
            Rt = ht ? function (t) {
                t.write(ut("")), t.close();
                var e = t.parentWindow.Object;
                return t = null, e
            }(ht) : ((e = n("iframe")).style.display = "none", jt.appendChild(e), e.src = String("javascript:"), (t = e.contentWindow.document).open(), t.write(ut("document.F=Object")), t.close(), t.F);
            for (var i = Ct.length; i--;) delete Rt.prototype[Ct[i]];
            return Rt()
        };
    Q[Bt] = !0;

    function Wt(t) {
        return w(se, t) || (Lt && w(ae, t) ? se[t] = ae[t] : se[t] = re("Symbol." + t)), se[t]
    }

    function Ft(t) {
        var e = mt.Symbol || (mt.Symbol = {});
        w(e, t) || ce(e, t, {value: le.f(t)})
    }

    function qt(t, e, i) {
        t && !w(t = i ? t : t.prototype, ue) && de(t, ue, {configurable: !0, value: e})
    }

    function Vt(t) {
        if ("function" != typeof t) throw TypeError(String(t) + " is not a function");
        return t
    }

    function Ut(t, e) {
        var i;
        return new (void 0 === (i = Mt(t) && ("function" == typeof (i = t.constructor) && (i === Array || Mt(i.prototype)) || m(i) && null === (i = i[he])) ? void 0 : i) ? Array : i)(0 === e ? 0 : e)
    }

    function Gt(f) {
        var m = 1 == f, g = 2 == f, v = 3 == f, b = 4 == f, y = 6 == f, w = 5 == f || y;
        return function (t, e, i, n) {
            for (var o, s, a, r, l = ct(t), c = x(l), d = (r = i, Vt(a = e), void 0 === r ? a : function (t, e, i) {
                return a.call(r, t, e, i)
            }), u = it(c.length), h = 0, n = n || Ut, p = m ? n(t, u) : g ? n(t, 0) : void 0; h < u; h++) if ((w || h in c) && (s = d(o = c[h], h, l), f)) if (m) p[h] = s; else if (s) switch (f) {
                case 3:
                    return !0;
                case 5:
                    return o;
                case 6:
                    return h;
                case 2:
                    pe.call(p, o)
            } else if (b) return !1;
            return y ? -1 : v || b ? b : p
        }
    }

    function Yt(t, e) {
        var i = $e[t] = ee(we.prototype);
        return ve(i, {type: ge, tag: t, description: e}), p || (i.description = e), i
    }

    function Xt(t, e, i) {
        return t === ye && Xt(Ee, e, i), E(t), e = v(e, !0), E(i), w($e, e) ? (i.enumerable ? (w(t, me) && t[me][e] && (t[me][e] = !1), i = ee(i, {enumerable: g(0, !1)})) : (w(t, me) || Ce(t, me, g(1, {})), t[me][e] = !0), ze(t, e, i)) : Ce(t, e, i)
    }

    function Kt(e, t) {
        E(e);
        var i = d(t), t = Nt(i).concat(te(i));
        return fe(t, function (t) {
            p && !Zt.call(i, t) || Xt(e, t, i[t])
        }), e
    }

    function Zt(t) {
        var e = v(t, !0), t = Te.call(this, e);
        return !(this === ye && w($e, e) && !w(Ee, e)) && (!(t || !w(this, e) || !w($e, e) || w(this, me) && this[me][e]) || t)
    }

    function Qt(t, e) {
        var i = d(t), t = v(e, !0);
        if (i !== ye || !w($e, t) || w(Ee, t)) {
            e = _e(i, t);
            return !e || !w($e, t) || w(i, me) && i[me][t] || (e.enumerable = !0), e
        }
    }

    function Jt(t) {
        var t = Se(d(t)), e = [];
        return fe(t, function (t) {
            w($e, t) || w(Q, t) || e.push(t)
        }), e
    }

    function te(t) {
        var e = t === ye, t = Se(e ? Ee : d(t)), i = [];
        return fe(t, function (t) {
            !w($e, t) || e && !w(ye, t) || i.push($e[t])
        }), i
    }

    var ee = Object.create || function (t, e) {
            var i;
            return null !== t ? (dt.prototype = E(t), i = new dt, dt.prototype = null, i[Bt] = t) : i = Rt(), void 0 === e ? i : Ht(i, e)
        }, ie = Tt.f, ne = {}.toString,
        oe = "object" == typeof window && window && Object.getOwnPropertyNames ? Object.getOwnPropertyNames(window) : [],
        z = {
            f: function (t) {
                return oe && "[object Window]" == ne.call(t) ? function (t) {
                    try {
                        return ie(t)
                    } catch (t) {
                        return oe.slice()
                    }
                }(t) : ie(d(t))
            }
        }, se = Y("wks"), ae = h.Symbol, re = o ? ae : D, le = {f: Wt}, ce = I.f, de = I.f, ue = Wt("toStringTag"),
        he = Wt("species"), pe = [].push,
        U = {forEach: Gt(0), map: Gt(1), filter: Gt(2), some: Gt(3), every: Gt(4), find: Gt(5), findIndex: Gt(6)},
        fe = U.forEach, me = L("hidden"), ge = "Symbol", G = Wt("toPrimitive"), ve = pt.set, be = pt.getterFor(ge),
        ye = Object.prototype, we = h.Symbol, xe = et("JSON", "stringify"), _e = $.f, Ce = I.f, Se = z.f, Te = f.f,
        $e = Y("symbols"), Ee = Y("op-symbols"), ke = Y("string-to-symbol-registry"),
        Ie = Y("symbol-to-string-registry"), J = Y("wks"), Y = h.QObject,
        Oe = !Y || !Y.prototype || !Y.prototype.findChild, ze = p && c(function () {
            return 7 != ee(Ce({}, "a", {
                get: function () {
                    return Ce(this, "a", {value: 7}).a
                }
            })).a
        }) ? function (t, e, i) {
            var n = _e(ye, e);
            n && delete ye[e], Ce(t, e, i), n && t !== ye && Ce(ye, e, n)
        } : Ce, Ae = Lt && "symbol" == typeof we.iterator ? function (t) {
            return "symbol" == typeof t
        } : function (t) {
            return Object(t) instanceof we
        };
    Lt || (ft((we = function () {
        if (this instanceof we) throw TypeError("Symbol is not a constructor");
        var t = arguments.length && void 0 !== arguments[0] ? String(arguments[0]) : void 0, e = D(t),
            i = function (t) {
                this === ye && i.call(Ee, t), w(this, me) && w(this[me], e) && (this[me][e] = !1), ze(this, e, g(1, t))
            };
        return p && Oe && ze(ye, e, {configurable: !0, set: i}), Yt(e, t)
    }).prototype, "toString", function () {
        return be(this).tag
    }), f.f = Zt, I.f = Xt, $.f = Qt, Tt.f = z.f = Jt, $t.f = te, p && (Ce(we.prototype, "description", {
        configurable: !0,
        get: function () {
            return be(this).description
        }
    }), ft(ye, "propertyIsEnumerable", Zt, {unsafe: !0}))), o || (le.f = function (t) {
        return Yt(Wt(t), t)
    }), lt({global: !0, wrap: !0, forced: !Lt, sham: !Lt}, {Symbol: we}), fe(Nt(J), function (t) {
        Ft(t)
    }), lt({target: ge, stat: !0, forced: !Lt}, {
        for: function (t) {
            var e = String(t);
            if (w(ke, e)) return ke[e];
            t = we(e);
            return ke[e] = t, Ie[t] = e, t
        }, keyFor: function (t) {
            if (!Ae(t)) throw TypeError(t + " is not a symbol");
            if (w(Ie, t)) return Ie[t]
        }, useSetter: function () {
            Oe = !0
        }, useSimple: function () {
            Oe = !1
        }
    }), lt({target: "Object", stat: !0, forced: !Lt, sham: !p}, {
        create: function (t, e) {
            return void 0 === e ? ee(t) : Kt(ee(t), e)
        }, defineProperty: Xt, defineProperties: Kt, getOwnPropertyDescriptor: Qt
    }), lt({target: "Object", stat: !0, forced: !Lt}, {
        getOwnPropertyNames: Jt,
        getOwnPropertySymbols: te
    }), lt({
        target: "Object", stat: !0, forced: c(function () {
            $t.f(1)
        })
    }, {
        getOwnPropertySymbols: function (t) {
            return $t.f(ct(t))
        }
    }), xe && (J = !Lt || c(function () {
        var t = we();
        return "[null]" != xe([t]) || "{}" != xe({a: t}) || "{}" != xe(Object(t))
    }), lt({target: "JSON", stat: !0, forced: J}, {
        stringify: function (t, e, i) {
            for (var n, o = [t], s = 1; s < arguments.length;) o.push(arguments[s++]);
            if ((m(n = e) || void 0 !== t) && !Ae(t)) return Mt(e) || (e = function (t, e) {
                if ("function" == typeof n && (e = n.call(this, t, e)), !Ae(e)) return e
            }), o[1] = e, xe.apply(null, o)
        }
    })), we.prototype[G] || O(we.prototype, G, we.prototype.valueOf), qt(we, ge), Q[me] = !0;
    var Pe, De, Le, Me, Ne, G = I.f, He = h.Symbol;
    !p || "function" != typeof He || "description" in He.prototype && void 0 === He().description || (Pe = {}, at(De = function () {
        var t = arguments.length < 1 || void 0 === arguments[0] ? void 0 : String(arguments[0]),
            e = this instanceof De ? new He(t) : void 0 === t ? He() : He(t);
        return "" === t && (Pe[e] = !0), e
    }, He), (Re = De.prototype = He.prototype).constructor = De, Le = Re.toString, Me = "Symbol(test)" == String(He("test")), Ne = /^Symbol\((.*)\)[^)]+$/, G(Re, "description", {
        configurable: !0,
        get: function () {
            var t = m(this) ? this.valueOf() : this, e = Le.call(t);
            if (w(Pe, t)) return "";
            e = Me ? e.slice(7, -1) : e.replace(Ne, "$1");
            return "" === e ? void 0 : e
        }
    }), lt({global: !0, forced: !0}, {Symbol: De})), Ft("iterator");

    function je(t, e, i) {
        (e = v(e)) in t ? I.f(t, e, g(0, i)) : t[e] = i
    }

    var Be, G = et("navigator", "userAgent") || "", Re = h.process, Re = Re && Re.versions, Re = Re && Re.v8;
    Re ? Ye = (Be = Re.split("."))[0] + Be[1] : G && (!(Be = G.match(/Edge\/(\d+)/)) || 74 <= Be[1]) && (Be = G.match(/Chrome\/(\d+)/)) && (Ye = Be[1]);

    function We(e) {
        return 51 <= Fe || !c(function () {
            var t = [];
            return (t.constructor = {})[qe] = function () {
                return {foo: 1}
            }, 1 !== t[e](Boolean).foo
        })
    }

    var Fe = Ye && +Ye, qe = Wt("species"), Ve = Wt("isConcatSpreadable"), Ue = 9007199254740991,
        Ge = "Maximum allowed index exceeded", Ye = 51 <= Fe || !c(function () {
            var t = [];
            return t[Ve] = !1, t.concat()[0] !== t
        }), G = We("concat");
    lt({target: "Array", proto: !0, forced: !Ye || !G}, {
        concat: function (t) {
            for (var e, i, n, o = ct(this), s = Ut(o, 0), a = 0, r = -1, l = arguments.length; r < l; r++) if (function (t) {
                if (!m(t)) return !1;
                var e = t[Ve];
                return void 0 !== e ? !!e : Mt(t)
            }(n = -1 === r ? o : arguments[r])) {
                if (a + (i = it(n.length)) > Ue) throw TypeError(Ge);
                for (e = 0; e < i; e++, a++) e in n && je(s, a, n[e])
            } else {
                if (Ue <= a) throw TypeError(Ge);
                je(s, a++, n)
            }
            return s.length = a, s
        }
    });
    var Xe = U.filter, Ye = We("filter"), G = Ye && !c(function () {
        [].filter.call({length: -1, 0: 1}, function (t) {
            throw t
        })
    });
    lt({target: "Array", proto: !0, forced: !Ye || !G}, {
        filter: function (t) {
            return Xe(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    });
    var Ke = Wt("unscopables"), Ze = Array.prototype;
    null == Ze[Ke] && I.f(Ze, Ke, {configurable: !0, value: ee(null)});

    function Qe(t) {
        Ze[Ke][t] = !0
    }

    var Je = U.find, ti = !0;
    "find" in [] && Array(1).find(function () {
        ti = !1
    }), lt({target: "Array", proto: !0, forced: ti}, {
        find: function (t) {
            return Je(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    }), Qe("find");
    var ei = U.findIndex, ii = !0;
    "findIndex" in [] && Array(1).findIndex(function () {
        ii = !1
    }), lt({target: "Array", proto: !0, forced: ii}, {
        findIndex: function (t) {
            return ei(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    }), Qe("findIndex");
    var ni = t.includes;
    lt({target: "Array", proto: !0}, {
        includes: function (t) {
            return ni(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    }), Qe("includes");

    function oi(t, e) {
        var i = [][t];
        return !i || !c(function () {
            i.call(null, e || function () {
                throw 1
            }, 1)
        })
    }

    var si = t.indexOf, ai = [].indexOf, ri = !!ai && 1 / [1].indexOf(1, -0) < 0, G = oi("indexOf");
    lt({target: "Array", proto: !0, forced: ri || G}, {
        indexOf: function (t) {
            return ri ? ai.apply(this, arguments) || 0 : si(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    });
    var t = !c(function () {
        function t() {
        }

        return t.prototype.constructor = null, Object.getPrototypeOf(new t) !== t.prototype
    }), li = L("IE_PROTO"), ci = Object.prototype, di = t ? Object.getPrototypeOf : function (t) {
        return t = ct(t), w(t, li) ? t[li] : "function" == typeof t.constructor && t instanceof t.constructor ? t.constructor.prototype : t instanceof Object ? ci : null
    }, G = Wt("iterator"), t = !1;
    [].keys && ("next" in (pi = [].keys()) ? (Ti = di(di(pi))) !== Object.prototype && (Ei = Ti) : t = !0), w(Ei = null == Ei ? {} : Ei, G) || O(Ei, G, function () {
        return this
    });

    function ui() {
        return this
    }

    function hi(t, e, i, n, o, s, a) {
        function r(t) {
            if (t === o && f) return f;
            if (!vi && t in h) return h[t];
            switch (t) {
                case"keys":
                case yi:
                case"entries":
                    return function () {
                        return new i(this, t)
                    }
            }
            return function () {
                return new i(this)
            }
        }

        m = e + " Iterator", (d = i).prototype = ee(fi, {next: g(1, n)}), qt(d, m, !1);
        var l, c, d = e + " Iterator", u = !1, h = t.prototype, p = h[bi] || h["@@iterator"] || o && h[o],
            f = !vi && p || r(o), m = "Array" == e && h.entries || p;
        if (m && (t = di(m.call(new t)), gi !== Object.prototype && t.next && (di(t) !== gi && (mi ? mi(t, gi) : "function" != typeof t[bi] && O(t, bi, ui)), qt(t, d, !0))), o == yi && p && p.name !== yi && (u = !0, f = function () {
            return p.call(this)
        }), h[bi] !== f && O(h, bi, f), o) if (l = {
            values: r(yi),
            keys: s ? f : r("keys"),
            entries: r("entries")
        }, a) for (c in l) !vi && !u && c in h || ft(h, c, l[c]); else lt({target: e, proto: !0, forced: vi || u}, l);
        return l
    }

    var pi = {IteratorPrototype: Ei, BUGGY_SAFARI_ITERATORS: t}, fi = pi.IteratorPrototype,
        mi = Object.setPrototypeOf || ("__proto__" in {} ? function () {
            var i, n = !1, t = {};
            try {
                (i = Object.getOwnPropertyDescriptor(Object.prototype, "__proto__").set).call(t, []), n = t instanceof Array
            } catch (i) {
            }
            return function (t, e) {
                return E(t), function (t) {
                    if (!m(t) && null !== t) throw TypeError("Can't set " + String(t) + " as a prototype")
                }(e), n ? i.call(t, e) : t.__proto__ = e, t
            }
        }() : void 0), gi = pi.IteratorPrototype, vi = pi.BUGGY_SAFARI_ITERATORS, bi = Wt("iterator"), yi = "values",
        wi = "Array Iterator", xi = pt.set, _i = pt.getterFor(wi), Ci = hi(Array, "Array", function (t, e) {
            xi(this, {type: wi, target: d(t), index: 0, kind: e})
        }, function () {
            var t = _i(this), e = t.target, i = t.kind, n = t.index++;
            return !e || n >= e.length ? {value: t.target = void 0, done: !0} : "keys" == i ? {
                value: n,
                done: !1
            } : "values" == i ? {value: e[n], done: !1} : {value: [n, e[n]], done: !1}
        }, "values");
    Qe("keys"), Qe("values"), Qe("entries");
    var Si = [].join, Ti = x != Object, G = oi("join", ",");
    lt({target: "Array", proto: !0, forced: Ti || G}, {
        join: function (t) {
            return Si.call(d(this), void 0 === t ? "," : t)
        }
    });
    var $i = U.map, Ei = We("map"), t = Ei && !c(function () {
        [].map.call({length: -1, 0: 1}, function (t) {
            throw t
        })
    });
    lt({target: "Array", proto: !0, forced: !Ei || !t}, {
        map: function (t) {
            return $i(this, t, 1 < arguments.length ? arguments[1] : void 0)
        }
    });
    var ki = [].reverse, pi = [1, 2];
    lt({target: "Array", proto: !0, forced: String(pi) === String(pi.reverse())}, {
        reverse: function () {
            return Mt(this) && (this.length = this.length), ki.call(this)
        }
    });
    var Ii = Wt("species"), Oi = [].slice, zi = Math.max;
    lt({target: "Array", proto: !0, forced: !We("slice")}, {
        slice: function (t, e) {
            var i, n, o, s = d(this), a = it(s.length), r = nt(t, a), l = nt(void 0 === e ? a : e, a);
            if (Mt(s) && ((i = "function" == typeof (i = s.constructor) && (i === Array || Mt(i.prototype)) || m(i) && null === (i = i[Ii]) ? void 0 : i) === Array || void 0 === i)) return Oi.call(s, r, l);
            for (n = new (void 0 === i ? Array : i)(zi(l - r, 0)), o = 0; r < l; r++, o++) r in s && je(n, o, s[r]);
            return n.length = o, n
        }
    });
    var Ai = [], Pi = Ai.sort, Ti = c(function () {
        Ai.sort(void 0)
    }), G = c(function () {
        Ai.sort(null)
    }), Ei = oi("sort");
    lt({target: "Array", proto: !0, forced: Ti || !G || Ei}, {
        sort: function (t) {
            return void 0 === t ? Pi.call(ct(this)) : Pi.call(ct(this), Vt(t))
        }
    });
    var Di = Math.max, Li = Math.min;
    lt({target: "Array", proto: !0, forced: !We("splice")}, {
        splice: function (t, e) {
            var i, n, o, s, a, r, l = ct(this), c = it(l.length), d = nt(t, c), t = arguments.length;
            if (0 === t ? i = n = 0 : n = 1 === t ? (i = 0, c - d) : (i = t - 2, Li(Di(bt(e), 0), c - d)), 9007199254740991 < c + i - n) throw TypeError("Maximum allowed length exceeded");
            for (o = Ut(l, n), s = 0; s < n; s++) (a = d + s) in l && je(o, s, l[a]);
            if (i < (o.length = n)) {
                for (s = d; s < c - n; s++) r = s + i, (a = s + n) in l ? l[r] = l[a] : delete l[r];
                for (s = c; c - n + i < s; s--) delete l[s - 1]
            } else if (n < i) for (s = c - n; d < s; s--) r = s + i - 1, (a = s + n - 1) in l ? l[r] = l[a] : delete l[r];
            for (s = 0; s < i; s++) l[s + d] = arguments[s + 2];
            return l.length = c - n + i, o
        }
    });

    function Mi(e) {
        return function (t) {
            t = String(b(t));
            return 1 & e && (t = t.replace(Bi, "")), t = 2 & e ? t.replace(Ri, "") : t
        }
    }

    function Ni(t) {
        var e, i, n, o, s, a, r, l = v(t, !1);
        if ("string" == typeof l && 2 < l.length) if (43 === (e = (l = qi(l)).charCodeAt(0)) || 45 === e) {
            if (88 === (t = l.charCodeAt(2)) || 120 === t) return NaN
        } else if (48 === e) {
            switch (l.charCodeAt(1)) {
                case 66:
                case 98:
                    i = 2, n = 49;
                    break;
                case 79:
                case 111:
                    i = 8, n = 55;
                    break;
                default:
                    return +l
            }
            for (s = (o = l.slice(2)).length, a = 0; a < s; a++) if ((r = o.charCodeAt(a)) < 48 || n < r) return NaN;
            return parseInt(o, i)
        }
        return +l
    }

    var Hi = function (t, e, i) {
            var n, o;
            return mi && "function" == typeof (n = e.constructor) && n !== i && m(o = n.prototype) && o !== i.prototype && mi(t, o), t
        }, ji = "\t\n\v\f\r                　\u2028\u2029\ufeff", t = "[" + ji + "]", Bi = RegExp("^" + t + t + "*"),
        Ri = RegExp(t + t + "*$"), pi = {start: Mi(1), end: Mi(2), trim: Mi(3)}, Ti = Tt.f, Wi = $.f, Fi = I.f,
        qi = pi.trim, Vi = "Number", Ui = h.Number, Gi = Ui.prototype, Yi = r(ee(Gi)) == Vi;
    if (Pt(Vi, !Ui(" 0o1") || !Ui("0b1") || Ui("+0x1"))) {
        for (var Xi, Ki = function (t) {
            var t = arguments.length < 1 ? 0 : t, e = this;
            return e instanceof Ki && (Yi ? c(function () {
                Gi.valueOf.call(e)
            }) : r(e) != Vi) ? Hi(new Ui(Ni(t)), e, Ki) : Ni(t)
        }, Zi = p ? Ti(Ui) : "MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(","), Qi = 0; Zi.length > Qi; Qi++) w(Ui, Xi = Zi[Qi]) && !w(Ki, Xi) && Fi(Ki, Xi, Wi(Ui, Xi));
        (Ki.prototype = Gi).constructor = Ki, ft(h, Vi, Ki)
    }
    var Ji = Object.assign, tn = Object.defineProperty, G = !Ji || c(function () {
        if (p && 1 !== Ji({b: 1}, Ji(tn({}, "a", {
            enumerable: !0, get: function () {
                tn(this, "b", {value: 3, enumerable: !1})
            }
        }), {b: 2})).b) return 1;
        var t = {}, e = {}, i = Symbol(), n = "abcdefghijklmnopqrst";
        return t[i] = 7, n.split("").forEach(function (t) {
            e[t] = t
        }), 7 != Ji({}, t)[i] || Nt(Ji({}, e)).join("") != n
    }) ? function (t, e) {
        for (var i = ct(t), n = arguments.length, o = 1, s = $t.f, a = f.f; o < n;) for (var r, l = x(arguments[o++]), c = s ? Nt(l).concat(s(l)) : Nt(l), d = c.length, u = 0; u < d;) r = c[u++], p && !a.call(l, r) || (i[r] = l[r]);
        return i
    } : Ji;
    lt({target: "Object", stat: !0, forced: Object.assign !== G}, {assign: G});

    function en(r) {
        return function (t) {
            for (var e, i = d(t), n = Nt(i), o = n.length, s = 0, a = []; s < o;) e = n[s++], p && !nn.call(i, e) || a.push(r ? [e, i[e]] : i[e]);
            return a
        }
    }

    var nn = f.f, on = [en(!0), en(!1)][0];
    lt({target: "Object", stat: !0}, {
        entries: function (t) {
            return on(t)
        }
    });
    Ei = {};
    Ei[Wt("toStringTag")] = "z";
    var t = "[object z]" === String(Ei), sn = Wt("toStringTag"), an = "Arguments" == r(function () {
        return arguments
    }()), rn = t ? r : function (t) {
        var e;
        return void 0 === t ? "Undefined" : null === t ? "Null" : "string" == typeof (t = function (t, e) {
            try {
                return t[e]
            } catch (t) {
            }
        }(e = Object(t), sn)) ? t : an ? r(e) : "Object" == (t = r(e)) && "function" == typeof e.callee ? "Arguments" : t
    };
    t || ft(Object.prototype, "toString", t ? {}.toString : function () {
        return "[object " + rn(this) + "]"
    }, {unsafe: !0});
    var ln = pi.trim, cn = h.parseFloat, Ti = 1 / cn(ji + "-0") != -1 / 0 ? function (t) {
        var e = ln(String(t)), t = cn(e);
        return 0 === t && "-" == e.charAt(0) ? -0 : t
    } : cn;
    lt({global: !0, forced: parseFloat != Ti}, {parseFloat: Ti});
    var dn = pi.trim, un = h.parseInt, hn = /^[+-]?0[Xx]/,
        G = 8 !== un(ji + "08") || 22 !== un(ji + "0x16") ? function (t, e) {
            t = dn(String(t));
            return un(t, e >>> 0 || (hn.test(t) ? 16 : 10))
        } : un;
    lt({global: !0, forced: parseInt != G}, {parseInt: G});
    var pn = Wt("match"), fn = function (t) {
        var e;
        return m(t) && (void 0 !== (e = t[pn]) ? !!e : "RegExp" == r(t))
    }, mn = function () {
        var t = E(this), e = "";
        return t.global && (e += "g"), t.ignoreCase && (e += "i"), t.multiline && (e += "m"), t.dotAll && (e += "s"), t.unicode && (e += "u"), t.sticky && (e += "y"), e
    };

    function gn(t, e) {
        return RegExp(t, e)
    }

    var Ei = {
            UNSUPPORTED_Y: c(function () {
                var t = gn("a", "y");
                return t.lastIndex = 2, null != t.exec("abcd")
            }), BROKEN_CARET: c(function () {
                var t = gn("^r", "gy");
                return t.lastIndex = 2, null != t.exec("str")
            })
        }, t = Wt("species"), vn = I.f, Ti = Tt.f, bn = pt.set, yn = Wt("match"), wn = h.RegExp, xn = wn.prototype,
        _n = /a/g, Cn = /a/g, Sn = new wn(_n) !== _n, Tn = Ei.UNSUPPORTED_Y;
    if (p && Pt("RegExp", !Sn || Tn || c(function () {
        return Cn[yn] = !1, wn(_n) != _n || wn(Cn) == Cn || "/a/i" != wn(_n, "i")
    }))) {
        for (var $n = function (t, e) {
            var i, n = this instanceof $n, o = fn(t), s = void 0 === e;
            if (!n && o && t.constructor === $n && s) return t;
            Sn ? o && !s && (t = t.source) : t instanceof $n && (s && (e = mn.call(t)), t = t.source), Tn && (i = !!e && -1 < e.indexOf("y")) && (e = e.replace(/y/g, ""));
            n = Hi(Sn ? new wn(t, e) : wn(t, e), n ? this : xn, $n);
            return Tn && i && bn(n, {sticky: i}), n
        }, En = Ti(wn), kn = 0; En.length > kn;) !function (e) {
            e in $n || vn($n, e, {
                configurable: !0, get: function () {
                    return wn[e]
                }, set: function (t) {
                    wn[e] = t
                }
            })
        }(En[kn++]);
        (xn.constructor = $n).prototype = xn, ft(h, "RegExp", $n)
    }
    G = et("RegExp"), Ti = I.f, p && G && !G[t] && Ti(G, t, {
        configurable: !0, get: function () {
            return this
        }
    });
    var In = RegExp.prototype.exec, On = String.prototype.replace, Ti = In,
        zn = (G = /a/, t = /b*/g, In.call(G, "a"), In.call(t, "a"), 0 !== G.lastIndex || 0 !== t.lastIndex),
        An = Ei.UNSUPPORTED_Y || Ei.BROKEN_CARET, Pn = void 0 !== /()??/.exec("")[1],
        Dn = Ti = zn || Pn || An ? function (t) {
            var e, i, n, o, s = this, a = An && s.sticky, r = mn.call(s), l = s.source, c = 0, d = t;
            return a && (-1 === (r = r.replace("y", "")).indexOf("g") && (r += "g"), d = String(t).slice(s.lastIndex), 0 < s.lastIndex && (!s.multiline || s.multiline && "\n" !== t[s.lastIndex - 1]) && (l = "(?: " + l + ")", d = " " + d, c++), i = new RegExp("^(?:" + l + ")", r)), Pn && (i = new RegExp("^" + l + "$(?!\\s)", r)), zn && (e = s.lastIndex), n = In.call(a ? i : s, d), a ? n ? (n.input = n.input.slice(c), n[0] = n[0].slice(c), n.index = s.lastIndex, s.lastIndex += n[0].length) : s.lastIndex = 0 : zn && n && (s.lastIndex = s.global ? n.index + n[0].length : e), Pn && n && 1 < n.length && On.call(n[0], i, function () {
                for (o = 1; o < arguments.length - 2; o++) void 0 === arguments[o] && (n[o] = void 0)
            }), n
        } : Ti;
    lt({target: "RegExp", proto: !0, forced: /./.exec !== Dn}, {exec: Dn});
    var Ln = RegExp.prototype, Mn = Ln.toString, Ei = c(function () {
        return "/a/b" != Mn.call({source: "a", flags: "b"})
    }), Ti = "toString" != Mn.name;
    (Ei || Ti) && ft(RegExp.prototype, "toString", function () {
        var t = E(this), e = String(t.source), i = t.flags;
        return "/" + e + "/" + String(void 0 === i && t instanceof RegExp && !("flags" in Ln) ? mn.call(t) : i)
    }, {unsafe: !0});

    function Nn(t) {
        if (fn(t)) throw TypeError("The method doesn't accept regular expressions");
        return t
    }

    function Hn(e) {
        var i = /./;
        try {
            "/./"[e](i)
        } catch (t) {
            try {
                return i[jn] = !1, "/./"[e](i)
            } catch (e) {
            }
        }
        return !1
    }

    var jn = Wt("match");
    lt({target: "String", proto: !0, forced: !Hn("includes")}, {
        includes: function (t) {
            return !!~String(b(this)).indexOf(Nn(t), 1 < arguments.length ? arguments[1] : void 0)
        }
    });

    function Bn(s) {
        return function (t, e) {
            var i, n = String(b(t)), o = bt(e), t = n.length;
            return o < 0 || t <= o ? s ? "" : void 0 : (e = n.charCodeAt(o)) < 55296 || 56319 < e || o + 1 === t || (i = n.charCodeAt(o + 1)) < 56320 || 57343 < i ? s ? n.charAt(o) : e : s ? n.slice(o, o + 2) : i - 56320 + (e - 55296 << 10) + 65536
        }
    }

    var Ti = {codeAt: Bn(!1), charAt: Bn(!0)}, Rn = Ti.charAt, Wn = "String Iterator", Fn = pt.set,
        qn = pt.getterFor(Wn);
    hi(String, "String", function (t) {
        Fn(this, {type: Wn, string: String(t), index: 0})
    }, function () {
        var t = qn(this), e = t.string, i = t.index;
        return i >= e.length ? {value: void 0, done: !0} : (i = Rn(e, i), t.index += i.length, {value: i, done: !1})
    });

    function Vn(i, t, e, n) {
        var s, o, a = Wt(i), r = !c(function () {
            var t = {};
            return t[a] = function () {
                return 7
            }, 7 != ""[i](t)
        }), l = r && !c(function () {
            var t = !1, e = /a/;
            return "split" === i && ((e = {}).constructor = {}, e.constructor[Yn] = function () {
                return e
            }, e.flags = "", e[a] = /./[a]), e.exec = function () {
                return t = !0, null
            }, e[a](""), !t
        });
        r && l && ("replace" !== i || Xn && Kn) && ("split" !== i || Zn) || (s = /./[a], e = (l = e(a, ""[i], function (t, e, i, n, o) {
            return e.exec === Dn ? r && !o ? {done: !0, value: s.call(e, i, n)} : {
                done: !0,
                value: t.call(i, e, n)
            } : {done: !1}
        }, {REPLACE_KEEPS_$0: Kn}))[0], o = l[1], ft(String.prototype, i, e), ft(RegExp.prototype, a, 2 == t ? function (t, e) {
            return o.call(t, this, e)
        } : function (t) {
            return o.call(t, this)
        })), n && O(RegExp.prototype[a], "sham", !0)
    }

    function Un(t, e, i) {
        return e + (i ? Qn(t, e).length : 1)
    }

    function Gn(t, e) {
        var i = t.exec;
        if ("function" == typeof i) {
            i = i.call(t, e);
            if ("object" != typeof i) throw TypeError("RegExp exec method returned something other than an Object or null");
            return i
        }
        if ("RegExp" !== r(t)) throw TypeError("RegExp#exec called on incompatible receiver");
        return Dn.call(t, e)
    }

    var Yn = Wt("species"), Xn = !c(function () {
            var t = /./;
            return t.exec = function () {
                var t = [];
                return t.groups = {a: "7"}, t
            }, "7" !== "".replace(t, "$<a>")
        }), Kn = "$0" === "a".replace(/./, "$0"), Zn = !c(function () {
            var t = /(?:)/, e = t.exec;
            t.exec = function () {
                return e.apply(this, arguments)
            };
            t = "ab".split(t);
            return 2 !== t.length || "a" !== t[0] || "b" !== t[1]
        }), Qn = Ti.charAt, Jn = Math.max, to = Math.min, eo = Math.floor, io = /\$([$&'`]|\d\d?|<[^>]*>)/g,
        no = /\$([$&'`]|\d\d?)/g;
    Vn("replace", 2, function (o, w, x, _) {
        return [function (t, e) {
            var i = b(this), n = null == t ? void 0 : t[o];
            return void 0 !== n ? n.call(t, i, e) : w.call(String(i), t, e)
        }, function (t, e) {
            if (_.REPLACE_KEEPS_$0 || "string" == typeof e && -1 === e.indexOf("$0")) {
                var i = x(w, t, this, e);
                if (i.done) return i.value
            }
            var n = E(t), o = String(this), s = "function" == typeof e;
            s || (e = String(e));
            var a, r = n.global;
            r && (a = n.unicode, n.lastIndex = 0);
            for (var l = []; ;) {
                var c = Gn(n, o);
                if (null === c) break;
                if (l.push(c), !r) break;
                "" === String(c[0]) && (n.lastIndex = Un(o, it(n.lastIndex), a))
            }
            for (var d, u = "", h = 0, p = 0; p < l.length; p++) {
                for (var c = l[p], f = String(c[0]), m = Jn(to(bt(c.index), o.length), 0), g = [], v = 1; v < c.length; v++) g.push(void 0 === (d = c[v]) ? d : String(d));
                var b, y = c.groups,
                    y = s ? (b = [f].concat(g, m, o), void 0 !== y && b.push(y), String(e.apply(void 0, b))) : function (s, a, r, l, c, t) {
                        var d = r + s.length, u = l.length, e = no;
                        return void 0 !== c && (c = ct(c), e = io), w.call(t, e, function (t, e) {
                            var i;
                            switch (e.charAt(0)) {
                                case"$":
                                    return "$";
                                case"&":
                                    return s;
                                case"`":
                                    return a.slice(0, r);
                                case"'":
                                    return a.slice(d);
                                case"<":
                                    i = c[e.slice(1, -1)];
                                    break;
                                default:
                                    var n = +e;
                                    if (0 == n) return t;
                                    if (u < n) {
                                        var o = eo(n / 10);
                                        return 0 !== o && o <= u ? void 0 === l[o - 1] ? e.charAt(1) : l[o - 1] + e.charAt(1) : t
                                    }
                                    i = l[n - 1]
                            }
                            return void 0 === i ? "" : i
                        })
                    }(f, o, m, g, y, e);
                h <= m && (u += o.slice(h, m) + y, h = m + f.length)
            }
            return u + o.slice(h)
        }]
    });
    var oo = Object.is || function (t, e) {
        return t === e ? 0 !== t || 1 / t == 1 / e : t != t && e != e
    };
    Vn("search", 1, function (n, o, s) {
        return [function (t) {
            var e = b(this), i = null == t ? void 0 : t[n];
            return void 0 !== i ? i.call(t, e) : new RegExp(t)[n](String(e))
        }, function (t) {
            var e = s(o, t, this);
            if (e.done) return e.value;
            var i = E(t), e = String(this), t = i.lastIndex;
            oo(t, 0) || (i.lastIndex = 0);
            e = Gn(i, e);
            return oo(i.lastIndex, t) || (i.lastIndex = t), null === e ? -1 : e.index
        }]
    });
    var so = Wt("species"), ao = [].push, ro = Math.min, lo = 4294967295, co = !c(function () {
        return !RegExp(lo, "y")
    });
    Vn("split", 2, function (o, m, g) {
        var v = "c" == "abbc".split(/(b)*/)[1] || 4 != "test".split(/(?:)/, -1).length || 2 != "ab".split(/(?:ab)*/).length || 4 != ".".split(/(.?)(.?)/).length || 1 < ".".split(/()()/).length || "".split(/.?/).length ? function (t, e) {
            var i = String(b(this)), n = void 0 === e ? lo : e >>> 0;
            if (0 == n) return [];
            if (void 0 === t) return [i];
            if (!fn(t)) return m.call(i, t, n);
            for (var o, s, a, r = [], e = (t.ignoreCase ? "i" : "") + (t.multiline ? "m" : "") + (t.unicode ? "u" : "") + (t.sticky ? "y" : ""), l = 0, c = new RegExp(t.source, e + "g"); (o = Dn.call(c, i)) && !((s = c.lastIndex) > l && (r.push(i.slice(l, o.index)), 1 < o.length && o.index < i.length && ao.apply(r, o.slice(1)), a = o[0].length, l = s, r.length >= n));) c.lastIndex === o.index && c.lastIndex++;
            return l === i.length ? !a && c.test("") || r.push("") : r.push(i.slice(l)), r.length > n ? r.slice(0, n) : r
        } : "0".split(void 0, 0).length ? function (t, e) {
            return void 0 === t && 0 === e ? [] : m.call(this, t, e)
        } : m;
        return [function (t, e) {
            var i = b(this), n = null == t ? void 0 : t[o];
            return void 0 !== n ? n.call(t, i, e) : v.call(String(i), t, e)
        }, function (t, e) {
            var i = g(v, t, this, e, v !== m);
            if (i.done) return i.value;
            var n = E(t), o = String(this),
                i = (i = RegExp, void 0 === (t = E(n).constructor) || null == (a = E(t)[so]) ? i : Vt(a)),
                s = n.unicode,
                a = (n.ignoreCase ? "i" : "") + (n.multiline ? "m" : "") + (n.unicode ? "u" : "") + (co ? "y" : "g"),
                r = new i(co ? n : "^(?:" + n.source + ")", a), l = void 0 === e ? lo : e >>> 0;
            if (0 == l) return [];
            if (0 === o.length) return null === Gn(r, o) ? [o] : [];
            for (var c = 0, d = 0, u = []; d < o.length;) {
                r.lastIndex = co ? d : 0;
                var h, p = Gn(r, co ? o : o.slice(d));
                if (null === p || (h = ro(it(r.lastIndex + (co ? 0 : d)), o.length)) === c) d = Un(o, d, s); else {
                    if (u.push(o.slice(c, d)), u.length === l) return u;
                    for (var f = 1; f <= p.length - 1; f++) if (u.push(p[f]), u.length === l) return u;
                    d = c = h
                }
            }
            return u.push(o.slice(c)), u
        }]
    }, !co);
    var uo, ho = pi.trim;
    lt({
        target: "String", proto: !0, forced: (uo = "trim", c(function () {
            return ji.trim() || "​᠎" != "​᠎".trim() || ji.trim.name !== uo
        }))
    }, {
        trim: function () {
            return ho(this)
        }
    });
    var po, fo = {
        CSSRuleList: 0,
        CSSStyleDeclaration: 0,
        CSSValueList: 0,
        ClientRectList: 0,
        DOMRectList: 0,
        DOMStringList: 0,
        DOMTokenList: 1,
        DataTransferItemList: 0,
        FileList: 0,
        HTMLAllCollection: 0,
        HTMLCollection: 0,
        HTMLFormElement: 0,
        HTMLSelectElement: 0,
        MediaList: 0,
        MimeTypeArray: 0,
        NamedNodeMap: 0,
        NodeList: 1,
        PaintRequestList: 0,
        Plugin: 0,
        PluginArray: 0,
        SVGLengthList: 0,
        SVGNumberList: 0,
        SVGPathSegList: 0,
        SVGPointList: 0,
        SVGStringList: 0,
        SVGTransformList: 0,
        SourceBufferList: 0,
        StyleSheetList: 0,
        TextTrackCueList: 0,
        TextTrackList: 0,
        TouchList: 0
    }, mo = U.forEach, go = oi("forEach") ? function (t) {
        return mo(this, t, 1 < arguments.length ? arguments[1] : void 0)
    } : [].forEach;
    for (po in fo) {
        var vo = h[po], vo = vo && vo.prototype;
        if (vo && vo.forEach !== go) try {
            O(vo, "forEach", go)
        } catch (N) {
            vo.forEach = go
        }
    }
    var bo, yo = Wt("iterator"), wo = Wt("toStringTag"), xo = Ci.values;
    for (bo in fo) {
        var _o = h[bo], Co = _o && _o.prototype;
        if (Co) {
            if (Co[yo] !== xo) try {
                O(Co, yo, xo)
            } catch (N) {
                Co[yo] = xo
            }
            if (Co[wo] || O(Co, wo, bo), fo[bo]) for (var So in Ci) if (Co[So] !== Ci[So]) try {
                O(Co, So, Ci[So])
            } catch (N) {
                Co[So] = Ci[So]
            }
        }
    }

    function To(t) {
        return (To = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    function $o(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    function Eo(t, e) {
        for (var i = 0; i < e.length; i++) {
            var n = e[i];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }

    function ko(t, e, i) {
        return e && Eo(t.prototype, e), i && Eo(t, i), t
    }

    function Io(t, e) {
        return function (t) {
            if (Array.isArray(t)) return t
        }(t) || function (t, e) {
            if (Symbol.iterator in Object(t) || "[object Arguments]" === Object.prototype.toString.call(t)) {
                var i = [], n = !0, o = !1, s = void 0;
                try {
                    for (var a, r = t[Symbol.iterator](); !(n = (a = r.next()).done) && (i.push(a.value), !e || i.length !== e); n = !0) ;
                } catch (t) {
                    o = !0, s = t
                } finally {
                    try {
                        n || null == r.return || r.return()
                    } finally {
                        if (o) throw s
                    }
                }
                return i
            }
        }(t, e) || function () {
            throw new TypeError("Invalid attempt to destructure non-iterable instance")
        }()
    }

    function Oo(t) {
        return function (t) {
            if (Array.isArray(t)) {
                for (var e = 0, i = new Array(t.length); e < t.length; e++) i[e] = t[e];
                return i
            }
        }(t) || function (t) {
            if (Symbol.iterator in Object(t) || "[object Arguments]" === Object.prototype.toString.call(t)) return Array.from(t)
        }(t) || function () {
            throw new TypeError("Invalid attempt to spread non-iterable instance")
        }()
    }

    pi = 4;
    try {
        var zo = N.fn.dropdown.Constructor.VERSION;
        void 0 !== zo && (pi = parseInt(zo, 10))
    } catch (N) {
    }
    try {
        var Ao = bootstrap.Tooltip.VERSION;
        void 0 !== Ao && (pi = parseInt(Ao, 10))
    } catch (N) {
    }
    U = {
        3: {
            iconsPrefix: "glyphicon",
            icons: {
                paginationSwitchDown: "glyphicon-collapse-down icon-chevron-down",
                paginationSwitchUp: "glyphicon-collapse-up icon-chevron-up",
                refresh: "glyphicon-refresh icon-refresh",
                toggleOff: "glyphicon-list-alt icon-list-alt",
                toggleOn: "glyphicon-list-alt icon-list-alt",
                columns: "glyphicon-th icon-th",
                detailOpen: "glyphicon-plus icon-plus",
                detailClose: "glyphicon-minus icon-minus",
                fullscreen: "glyphicon-fullscreen",
                search: "glyphicon-search",
                clearSearch: "glyphicon-trash"
            },
            classes: {
                buttonsPrefix: "btn",
                buttons: "default",
                buttonsGroup: "btn-group",
                buttonsDropdown: "btn-group",
                pull: "pull",
                inputGroup: "input-group",
                inputPrefix: "input-",
                input: "form-control",
                paginationDropdown: "btn-group dropdown",
                dropup: "dropup",
                dropdownActive: "active",
                paginationActive: "active",
                buttonActive: "active"
            },
            html: {
                toolbarDropdown: ['<ul class="dropdown-menu" role="menu">', "</ul>"],
                toolbarDropdownItem: '<li class="dropdown-item-marker" role="menuitem"><label>%s</label></li>',
                toolbarDropdownSeparator: '<li class="divider"></li>',
                pageDropdown: ['<ul class="dropdown-menu" role="menu">', "</ul>"],
                pageDropdownItem: '<li role="menuitem" class="%s"><a href="#">%s</a></li>',
                dropdownCaret: '<span class="caret"></span>',
                pagination: ['<ul class="pagination%s">', "</ul>"],
                paginationItem: '<li class="page-item%s"><a class="page-link" aria-label="%s" href="javascript:void(0)">%s</a></li>',
                icon: '<i class="%s %s"></i>',
                inputGroup: '<div class="input-group">%s<span class="input-group-btn">%s</span></div>',
                searchInput: '<input class="%s%s" type="text" placeholder="%s">',
                searchButton: '<button class="%s" type="button" name="search" title="%s">%s %s</button>',
                searchClearButton: '<button class="%s" type="button" name="clearSearch" title="%s">%s %s</button>'
            }
        }, 4: {
            iconsPrefix: "fa",
            icons: {
                paginationSwitchDown: "fa-caret-square-down",
                paginationSwitchUp: "fa-caret-square-up",
                refresh: "fa-sync",
                toggleOff: "fa-toggle-off",
                toggleOn: "fa-toggle-on",
                columns: "fa-th-list",
                detailOpen: "fa-plus",
                detailClose: "fa-minus",
                fullscreen: "fa-arrows-alt",
                search: "fa-search",
                clearSearch: "fa-trash"
            },
            classes: {
                buttonsPrefix: "btn",
                buttons: "secondary",
                buttonsGroup: "btn-group",
                buttonsDropdown: "btn-group",
                pull: "float",
                inputGroup: "btn-group",
                inputPrefix: "form-control-",
                input: "form-control",
                paginationDropdown: "btn-group dropdown",
                dropup: "dropup",
                dropdownActive: "active",
                paginationActive: "active",
                buttonActive: "active"
            },
            html: {
                toolbarDropdown: ['<div class="dropdown-menu dropdown-menu-right">', "</div>"],
                toolbarDropdownItem: '<label class="dropdown-item dropdown-item-marker">%s</label>',
                pageDropdown: ['<div class="dropdown-menu">', "</div>"],
                pageDropdownItem: '<a class="dropdown-item %s" href="#">%s</a>',
                toolbarDropdownSeparator: '<div class="dropdown-divider"></div>',
                dropdownCaret: '<span class="caret"></span>',
                pagination: ['<ul class="pagination%s">', "</ul>"],
                paginationItem: '<li class="page-item%s"><a class="page-link" aria-label="%s" href="javascript:void(0)">%s</a></li>',
                icon: '<i class="%s %s"></i>',
                inputGroup: '<div class="input-group">%s<div class="input-group-append">%s</div></div>',
                searchInput: '<input class="%s%s" type="text" placeholder="%s">',
                searchButton: '<button class="%s" type="button" name="search" title="%s">%s %s</button>',
                searchClearButton: '<button class="%s" type="button" name="clearSearch" title="%s">%s %s</button>'
            }
        }, 5: {
            iconsPrefix: "fa",
            icons: {
                paginationSwitchDown: "fa-caret-square-down",
                paginationSwitchUp: "fa-caret-square-up",
                refresh: "fa-sync",
                toggleOff: "fa-toggle-off",
                toggleOn: "fa-toggle-on",
                columns: "fa-th-list",
                detailOpen: "fa-plus",
                detailClose: "fa-minus",
                fullscreen: "fa-arrows-alt",
                search: "fa-search",
                clearSearch: "fa-trash"
            },
            classes: {
                buttonsPrefix: "btn",
                buttons: "secondary",
                buttonsGroup: "btn-group",
                buttonsDropdown: "btn-group",
                pull: "float",
                inputGroup: "btn-group",
                inputPrefix: "form-control-",
                input: "form-control",
                paginationDropdown: "btn-group dropdown",
                dropup: "dropup",
                dropdownActive: "active",
                paginationActive: "active",
                buttonActive: "active"
            },
            html: {
                toolbarDropdown: ['<div class="dropdown-menu dropdown-menu-right">', "</div>"],
                toolbarDropdownItem: '<label class="dropdown-item dropdown-item-marker">%s</label>',
                pageDropdown: ['<div class="dropdown-menu">', "</div>"],
                pageDropdownItem: '<a class="dropdown-item %s" href="#">%s</a>',
                toolbarDropdownSeparator: '<div class="dropdown-divider"></div>',
                dropdownCaret: '<span class="caret"></span>',
                pagination: ['<ul class="pagination%s">', "</ul>"],
                paginationItem: '<li class="page-item%s"><a class="page-link" aria-label="%s" href="javascript:void(0)">%s</a></li>',
                icon: '<i class="%s %s"></i>',
                inputGroup: '<div class="input-group">%s<div class="input-group-append">%s</div></div>',
                searchInput: '<input class="%s%s" type="text" placeholder="%s">',
                searchButton: '<button class="%s" type="button" name="search" title="%s">%s %s</button>',
                searchClearButton: '<button class="%s" type="button" name="clearSearch" title="%s">%s %s</button>'
            }
        }
    }[pi], zo = {
        height: void 0,
        classes: "table table-bordered table-hover",
        buttons: {},
        theadClasses: "",
        headerStyle: function (t) {
            return {}
        },
        rowStyle: function (t, e) {
            return {}
        },
        rowAttributes: function (t, e) {
            return {}
        },
        undefinedText: "-",
        locale: void 0,
        virtualScroll: !1,
        virtualScrollItemHeight: void 0,
        sortable: !0,
        sortClass: void 0,
        silentSort: !0,
        sortName: void 0,
        sortOrder: void 0,
        sortReset: !1,
        sortStable: !1,
        rememberOrder: !1,
        serverSort: !0,
        customSort: void 0,
        columns: [[]],
        data: [],
        url: void 0,
        method: "get",
        cache: !0,
        contentType: "application/json",
        dataType: "json",
        ajax: void 0,
        ajaxOptions: {},
        queryParams: function (t) {
            return t
        },
        queryParamsType: "limit",
        responseHandler: function (t) {
            return t
        },
        totalField: "total",
        totalNotFilteredField: "totalNotFiltered",
        dataField: "rows",
        footerField: "footer",
        pagination: !1,
        paginationParts: ["pageInfo", "pageSize", "pageList"],
        showExtendedPagination: !1,
        paginationLoop: !0,
        sidePagination: "client",
        totalRows: 0,
        totalNotFiltered: 0,
        pageNumber: 1,
        pageSize: 10,
        pageList: [10, 25, 50, 100],
        paginationHAlign: "right",
        paginationVAlign: "bottom",
        paginationDetailHAlign: "left",
        paginationPreText: "&lsaquo;",
        paginationNextText: "&rsaquo;",
        paginationSuccessivelySize: 5,
        paginationPagesBySide: 1,
        paginationUseIntermediate: !1,
        search: !1,
        searchHighlight: !1,
        searchOnEnterKey: !1,
        strictSearch: !1,
        searchSelector: !1,
        visibleSearch: !1,
        showButtonIcons: !0,
        showButtonText: !1,
        showSearchButton: !1,
        showSearchClearButton: !1,
        trimOnSearch: !0,
        searchAlign: "right",
        searchTimeOut: 500,
        searchText: "",
        customSearch: void 0,
        showHeader: !0,
        showFooter: !1,
        footerStyle: function (t) {
            return {}
        },
        searchAccentNeutralise: !1,
        showColumns: !1,
        showColumnsToggleAll: !1,
        showColumnsSearch: !1,
        minimumCountColumns: 1,
        showPaginationSwitch: !1,
        showRefresh: !1,
        showToggle: !1,
        showFullscreen: !1,
        smartDisplay: !0,
        escape: !1,
        filterOptions: {filterAlgorithm: "and"},
        idField: void 0,
        selectItemName: "btSelectItem",
        clickToSelect: !1,
        ignoreClickToSelectOn: function (t) {
            t = t.tagName;
            return ["A", "BUTTON"].includes(t)
        },
        singleSelect: !1,
        checkboxHeader: !0,
        maintainMetaData: !1,
        multipleSelectRow: !1,
        uniqueId: void 0,
        cardView: !1,
        detailView: !1,
        detailViewIcon: !0,
        detailViewByClick: !1,
        detailViewAlign: "left",
        detailFormatter: function (t, e) {
            return ""
        },
        detailFilter: function (t, e) {
            return !0
        },
        toolbar: void 0,
        toolbarAlign: "left",
        buttonsToolbar: void 0,
        buttonsAlign: "right",
        buttonsOrder: ["paginationSwitch", "refresh", "toggle", "fullscreen", "columns"],
        buttonsPrefix: U.classes.buttonsPrefix,
        buttonsClass: U.classes.buttons,
        icons: U.icons,
        iconSize: void 0,
        iconsPrefix: U.iconsPrefix,
        loadingFontSize: "auto",
        loadingTemplate: function (t) {
            return '<span class="loading-wrap">\n      <span class="loading-text">'.concat(t, '</span>\n      <span class="animation-wrap"><span class="animation-dot"></span></span>\n      </span>\n    ')
        },
        onAll: function (t, e) {
            return !1
        },
        onClickCell: function (t, e, i, n) {
            return !1
        },
        onDblClickCell: function (t, e, i, n) {
            return !1
        },
        onClickRow: function (t, e) {
            return !1
        },
        onDblClickRow: function (t, e) {
            return !1
        },
        onSort: function (t, e) {
            return !1
        },
        onCheck: function (t) {
            return !1
        },
        onUncheck: function (t) {
            return !1
        },
        onCheckAll: function (t) {
            return !1
        },
        onUncheckAll: function (t) {
            return !1
        },
        onCheckSome: function (t) {
            return !1
        },
        onUncheckSome: function (t) {
            return !1
        },
        onLoadSuccess: function (t) {
            return !1
        },
        onLoadError: function (t) {
            return !1
        },
        onColumnSwitch: function (t, e) {
            return !1
        },
        onPageChange: function (t, e) {
            return !1
        },
        onSearch: function (t) {
            return !1
        },
        onToggle: function (t) {
            return !1
        },
        onPreBody: function (t) {
            return !1
        },
        onPostBody: function () {
            return !1
        },
        onPostHeader: function () {
            return !1
        },
        onPostFooter: function () {
            return !1
        },
        onExpandRow: function (t, e, i) {
            return !1
        },
        onCollapseRow: function (t, e) {
            return !1
        },
        onRefreshOptions: function (t) {
            return !1
        },
        onRefresh: function (t) {
            return !1
        },
        onResetView: function () {
            return !1
        },
        onScrollBody: function () {
            return !1
        }
    }, Ao = {
        formatLoadingMessage: function () {
            return "Loading, please wait"
        }, formatRecordsPerPage: function (t) {
            return "".concat(t, " rows per page")
        }, formatShowingRows: function (t, e, i, n) {
            return void 0 !== n && 0 < n && i < n ? "Showing ".concat(t, " to ").concat(e, " of ").concat(i, " rows (filtered from ").concat(n, " total rows)") : "Showing ".concat(t, " to ").concat(e, " of ").concat(i, " rows")
        }, formatSRPaginationPreText: function () {
            return "previous page"
        }, formatSRPaginationPageText: function (t) {
            return "to page ".concat(t)
        }, formatSRPaginationNextText: function () {
            return "next page"
        }, formatDetailPagination: function (t) {
            return "Showing ".concat(t, " rows")
        }, formatSearch: function () {
            return "Search"
        }, formatClearSearch: function () {
            return "Clear Search"
        }, formatNoMatches: function () {
            return "No matching records found"
        }, formatPaginationSwitch: function () {
            return "Hide/Show pagination"
        }, formatPaginationSwitchDown: function () {
            return "Show pagination"
        }, formatPaginationSwitchUp: function () {
            return "Hide pagination"
        }, formatRefresh: function () {
            return "Refresh"
        }, formatToggle: function () {
            return "Toggle"
        }, formatToggleOn: function () {
            return "Show card view"
        }, formatToggleOff: function () {
            return "Hide card view"
        }, formatColumns: function () {
            return "Columns"
        }, formatColumnsToggleAll: function () {
            return "Toggle all"
        }, formatFullscreen: function () {
            return "Fullscreen"
        }, formatAllRows: function () {
            return "All"
        }
    };
    Object.assign(zo, Ao);
    var Po = {
        VERSION: "1.18.0",
        THEME: "bootstrap".concat(pi),
        CONSTANTS: U,
        DEFAULTS: zo,
        COLUMN_DEFAULTS: {
            field: void 0,
            title: void 0,
            titleTooltip: void 0,
            class: void 0,
            width: void 0,
            widthUnit: "px",
            rowspan: void 0,
            colspan: void 0,
            align: void 0,
            halign: void 0,
            falign: void 0,
            valign: void 0,
            cellStyle: void 0,
            radio: !1,
            checkbox: !1,
            checkboxEnabled: !0,
            clickToSelect: !0,
            showSelectTitle: !1,
            sortable: !1,
            sortName: void 0,
            order: "asc",
            sorter: void 0,
            visible: !0,
            switchable: !0,
            cardVisible: !0,
            searchable: !0,
            formatter: void 0,
            footerFormatter: void 0,
            detailFormatter: void 0,
            searchFormatter: !0,
            searchHighlightFormatter: !1,
            escape: !1,
            events: void 0
        },
        METHODS: ["getOptions", "refreshOptions", "getData", "getSelections", "load", "append", "prepend", "remove", "removeAll", "insertRow", "updateRow", "getRowByUniqueId", "updateByUniqueId", "removeByUniqueId", "updateCell", "updateCellByUniqueId", "showRow", "hideRow", "getHiddenRows", "showColumn", "hideColumn", "getVisibleColumns", "getHiddenColumns", "showAllColumns", "hideAllColumns", "mergeCells", "checkAll", "uncheckAll", "checkInvert", "check", "uncheck", "checkBy", "uncheckBy", "refresh", "destroy", "resetView", "showLoading", "hideLoading", "togglePagination", "toggleFullscreen", "toggleView", "resetSearch", "filterBy", "scrollTo", "getScrollPosition", "selectPage", "prevPage", "nextPage", "toggleDetailView", "expandRow", "collapseRow", "expandRowByUniqueId", "collapseRowByUniqueId", "expandAllRows", "collapseAllRows", "updateColumnTitle", "updateFormatText"],
        EVENTS: {
            "all.bs.table": "onAll",
            "click-row.bs.table": "onClickRow",
            "dbl-click-row.bs.table": "onDblClickRow",
            "click-cell.bs.table": "onClickCell",
            "dbl-click-cell.bs.table": "onDblClickCell",
            "sort.bs.table": "onSort",
            "check.bs.table": "onCheck",
            "uncheck.bs.table": "onUncheck",
            "check-all.bs.table": "onCheckAll",
            "uncheck-all.bs.table": "onUncheckAll",
            "check-some.bs.table": "onCheckSome",
            "uncheck-some.bs.table": "onUncheckSome",
            "load-success.bs.table": "onLoadSuccess",
            "load-error.bs.table": "onLoadError",
            "column-switch.bs.table": "onColumnSwitch",
            "page-change.bs.table": "onPageChange",
            "search.bs.table": "onSearch",
            "toggle.bs.table": "onToggle",
            "pre-body.bs.table": "onPreBody",
            "post-body.bs.table": "onPostBody",
            "post-header.bs.table": "onPostHeader",
            "post-footer.bs.table": "onPostFooter",
            "expand-row.bs.table": "onExpandRow",
            "collapse-row.bs.table": "onCollapseRow",
            "refresh-options.bs.table": "onRefreshOptions",
            "reset-view.bs.table": "onResetView",
            "refresh.bs.table": "onRefresh",
            "scroll-body.bs.table": "onScrollBody"
        },
        LOCALES: {en: Ao, "en-US": Ao}
    }, zo = c(function () {
        Nt(1)
    });
    lt({target: "Object", stat: !0, forced: zo}, {
        keys: function (t) {
            return Nt(ct(t))
        }
    });
    var Ao = $.f, Do = "".endsWith, Lo = Math.min, zo = Hn("endsWith"),
        Mo = !(zo || (!(Mo = Ao(String.prototype, "endsWith")) || Mo.writable));
    lt({target: "String", proto: !0, forced: !Mo && !zo}, {
        endsWith: function (t) {
            var e = String(b(this));
            Nn(t);
            var i = 1 < arguments.length ? arguments[1] : void 0, n = it(e.length), n = void 0 === i ? n : Lo(it(i), n),
                t = String(t);
            return Do ? Do.call(e, t, n) : e.slice(n - t.length, n) === t
        }
    });
    var Mo = $.f, No = "".startsWith, Ho = Math.min, zo = Hn("startsWith"),
        jo = !(zo || (!(jo = Mo(String.prototype, "startsWith")) || jo.writable));
    lt({target: "String", proto: !0, forced: !jo && !zo}, {
        startsWith: function (t) {
            var e = String(b(this));
            Nn(t);
            var i = it(Ho(1 < arguments.length ? arguments[1] : void 0, e.length)), t = String(t);
            return No ? No.call(e, t, i) : e.slice(i, i + t.length) === t
        }
    });
    var Bo = {
        getSearchInput: function (t) {
            return "string" == typeof t.options.searchSelector ? N(t.options.searchSelector) : t.$toolbar.find(".search input")
        }, sprintf: function (t) {
            for (var e = arguments.length, i = new Array(1 < e ? e - 1 : 0), n = 1; n < e; n++) i[n - 1] = arguments[n];
            var o = !0, s = 0, t = t.replace(/%s/g, function () {
                var t = i[s++];
                return void 0 === t ? (o = !1, "") : t
            });
            return o ? t : ""
        }, isObject: function (t) {
            return t instanceof Object && !Array.isArray(t)
        }, isEmptyObject: function () {
            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
            return 0 === Object.entries(t).length && t.constructor === Object
        }, isNumeric: function (t) {
            return !isNaN(parseFloat(t)) && isFinite(t)
        }, getFieldTitle: function (t, e) {
            var i = !0, n = !1, o = void 0;
            try {
                for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done); i = !0) {
                    var r = s.value;
                    if (r.field === e) return r.title
                }
            } catch (t) {
                n = !0, o = t
            } finally {
                try {
                    i || null == a.return || a.return()
                } finally {
                    if (n) throw o
                }
            }
            return ""
        }, setFieldIndex: function (t) {
            var e = 0, i = [], n = !0, o = !1, s = void 0;
            try {
                for (var a, r = t[0][Symbol.iterator](); !(n = (a = r.next()).done); n = !0) e += a.value.colspan || 1
            } catch (t) {
                o = !0, s = t
            } finally {
                try {
                    n || null == r.return || r.return()
                } finally {
                    if (o) throw s
                }
            }
            for (var l = 0; l < t.length; l++) {
                i[l] = [];
                for (var c = 0; c < e; c++) i[l][c] = !1
            }
            for (var d = 0; d < t.length; d++) {
                var u = !0, h = !1, p = void 0;
                try {
                    for (var f, m = t[d][Symbol.iterator](); !(u = (f = m.next()).done); u = !0) {
                        var g = f.value, v = g.rowspan || 1, b = g.colspan || 1, y = i[d].indexOf(!1);
                        g.colspanIndex = y, 1 === b ? (g.fieldIndex = y, void 0 === g.field && (g.field = y)) : g.colspanGroup = g.colspan;
                        for (var w = 0; w < v; w++) for (var x = 0; x < b; x++) i[d + w][y + x] = !0
                    }
                } catch (t) {
                    h = !0, p = t
                } finally {
                    try {
                        u || null == m.return || m.return()
                    } finally {
                        if (h) throw p
                    }
                }
            }
        }, normalizeAccent: function (t) {
            return "string" != typeof t ? t : t.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
        }, updateFieldGroup: function (t) {
            var i = (o = []).concat.apply(o, Oo(t)), e = !0, n = !1, o = void 0;
            try {
                for (var s, a = t[Symbol.iterator](); !(e = (s = a.next()).done); e = !0) {
                    var r = s.value, l = !0, c = !1, d = void 0;
                    try {
                        for (var u, h = r[Symbol.iterator](); !(l = (u = h.next()).done); l = !0) {
                            var p = u.value;
                            if (1 < p.colspanGroup) {
                                for (var f = 0, m = p.colspanIndex; m < p.colspanIndex + p.colspanGroup; m++) !function (e) {
                                    i.find(function (t) {
                                        return t.fieldIndex === e
                                    }).visible && f++
                                }(m);
                                p.colspan = f, p.visible = 0 < f
                            }
                        }
                    } catch (t) {
                        c = !0, d = t
                    } finally {
                        try {
                            l || null == h.return || h.return()
                        } finally {
                            if (c) throw d
                        }
                    }
                }
            } catch (t) {
                n = !0, o = t
            } finally {
                try {
                    e || null == a.return || a.return()
                } finally {
                    if (n) throw o
                }
            }
        }, getScrollBarWidth: function () {
            var t, e, i;
            return void 0 === this.cachedWidth && (i = N("<div/>").addClass("fixed-table-scroll-inner"), (t = N("<div/>").addClass("fixed-table-scroll-outer")).append(i), N("body").append(t), e = i[0].offsetWidth, t.css("overflow", "scroll"), e === (i = i[0].offsetWidth) && (i = t[0].clientWidth), t.remove(), this.cachedWidth = e - i), this.cachedWidth
        }, calculateObjectValue: function (t, e) {
            var i = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : [],
                n = 3 < arguments.length ? arguments[3] : void 0, o = e;
            if ("string" == typeof e) {
                var s = e.split(".");
                if (1 < s.length) {
                    o = window;
                    var a = !0, r = !1, l = void 0;
                    try {
                        for (var c, d = s[Symbol.iterator](); !(a = (c = d.next()).done); a = !0) o = o[c.value]
                    } catch (t) {
                        r = !0, l = t
                    } finally {
                        try {
                            a || null == d.return || d.return()
                        } finally {
                            if (r) throw l
                        }
                    }
                } else o = window[e]
            }
            return null !== o && "object" === To(o) ? o : "function" == typeof o ? o.apply(t, i || []) : !o && "string" == typeof e && this.sprintf.apply(this, [e].concat(Oo(i))) ? this.sprintf.apply(this, [e].concat(Oo(i))) : n
        }, compareObjects: function (t, e, i) {
            var n = Object.keys(t), o = Object.keys(e);
            if (i && n.length !== o.length) return !1;
            for (var s = 0, a = n; s < a.length; s++) {
                var r = a[s];
                if (o.includes(r) && t[r] !== e[r]) return !1
            }
            return !0
        }, escapeHTML: function (t) {
            return "string" == typeof t ? t.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/`/g, "&#x60;") : t
        }, unescapeHTML: function (t) {
            return "string" == typeof t ? t.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&#x60;/g, "`") : t
        }, getRealDataAttr: function (t) {
            for (var e = 0, i = Object.entries(t); e < i.length; e++) {
                var n = Io(i[e], 2), o = n[0], s = n[1], n = o.split(/(?=[A-Z])/).join("-").toLowerCase();
                n !== o && (t[n] = s, delete t[o])
            }
            return t
        }, getItemField: function (t, e, i) {
            var n = t;
            if ("string" != typeof e || t.hasOwnProperty(e)) return i ? this.escapeHTML(t[e]) : t[e];
            var o = e.split("."), s = !0, a = !1, e = void 0;
            try {
                for (var r, l = o[Symbol.iterator](); !(s = (r = l.next()).done); s = !0) var c = r.value, n = n && n[c]
            } catch (t) {
                a = !0, e = t
            } finally {
                try {
                    s || null == l.return || l.return()
                } finally {
                    if (a) throw e
                }
            }
            return i ? this.escapeHTML(n) : n
        }, isIEBrowser: function () {
            return navigator.userAgent.includes("MSIE ") || /Trident.*rv:11\./.test(navigator.userAgent)
        }, findIndex: function (t, e) {
            var i = !0, n = !1, o = void 0;
            try {
                for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done); i = !0) {
                    var r = s.value;
                    if (JSON.stringify(r) === JSON.stringify(e)) return t.indexOf(r)
                }
            } catch (t) {
                n = !0, o = t
            } finally {
                try {
                    i || null == a.return || a.return()
                } finally {
                    if (n) throw o
                }
            }
            return -1
        }, trToData: function (c, t) {
            var d = this, e = [], u = [];
            return t.each(function (r, t) {
                var t = N(t), l = {};
                l._id = t.attr("id"), l._class = t.attr("class"), l._data = d.getRealDataAttr(t.data()), l._style = t.attr("style"), t.find(">td,>th").each(function (t, e) {
                    for (var e = N(e), i = +e.attr("colspan") || 1, n = +e.attr("rowspan") || 1, o = t; u[r] && u[r][o]; o++) ;
                    for (var s = o; s < o + i; s++) for (var a = r; a < r + n; a++) u[a] || (u[a] = []), u[a][s] = !0;
                    t = c[o].field;
                    l[t] = e.html().trim(), l["_".concat(t, "_id")] = e.attr("id"), l["_".concat(t, "_class")] = e.attr("class"), l["_".concat(t, "_rowspan")] = e.attr("rowspan"), l["_".concat(t, "_colspan")] = e.attr("colspan"), l["_".concat(t, "_title")] = e.attr("title"), l["_".concat(t, "_data")] = d.getRealDataAttr(e.data()), l["_".concat(t, "_style")] = e.attr("style")
                }), e.push(l)
            }), e
        }, sort: function (t, e, i, n, o, s) {
            return null == t && (t = ""), null == e && (e = ""), n && t === e && (t = o, e = s), this.isNumeric(t) && this.isNumeric(e) ? (t = parseFloat(t)) < (e = parseFloat(e)) ? -1 * i : e < t ? i : 0 : t === e ? 0 : -1 === (t = "string" != typeof t ? t.toString() : t).localeCompare(e) ? -1 * i : i
        }, getEventName: function (t) {
            var e = (e = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "") || "".concat(+new Date).concat(~~(1e6 * Math.random()));
            return "".concat(t, "-").concat(e)
        }, hasDetailViewIcon: function (t) {
            return t.detailView && t.detailViewIcon && !t.cardView
        }, getDetailViewIndexOffset: function (t) {
            return this.hasDetailViewIcon(t) && "right" !== t.detailViewAlign ? 1 : 0
        }, checkAutoMergeCells: function (t) {
            var e = !0, i = !1, n = void 0;
            try {
                for (var o, s = t[Symbol.iterator](); !(e = (o = s.next()).done); e = !0) for (var a = o.value, r = 0, l = Object.keys(a); r < l.length; r++) {
                    var c = l[r];
                    if (c.startsWith("_") && (c.endsWith("_rowspan") || c.endsWith("_colspan"))) return !0
                }
            } catch (t) {
                i = !0, n = t
            } finally {
                try {
                    e || null == s.return || s.return()
                } finally {
                    if (i) throw n
                }
            }
            return !1
        }, deepCopy: function (t) {
            return void 0 === t ? t : N.extend(!0, Array.isArray(t) ? [] : {}, t)
        }
    }, Ro = (ko(qo, [{
        key: "initDOM", value: function (t, e) {
            void 0 === this.clusterHeight && (this.cache.scrollTop = this.scrollEl.scrollTop, this.cache.data = this.contentEl.innerHTML = t[0] + t[0] + t[0], this.getRowsHeight(t));
            var i = this.initData(t, this.getNum(e)), n = i.rows.join(""), o = this.checkChanges("data", n),
                s = this.checkChanges("top", i.topOffset), a = this.checkChanges("bottom", i.bottomOffset), t = [];
            o && s ? (i.topOffset && t.push(this.getExtra("top", i.topOffset)), t.push(n), i.bottomOffset && t.push(this.getExtra("bottom", i.bottomOffset)), this.contentEl.innerHTML = t.join(""), e && (this.contentEl.scrollTop = this.cache.scrollTop)) : a && (this.contentEl.lastChild.style.height = "".concat(i.bottomOffset, "px"))
        }
    }, {
        key: "getRowsHeight", value: function () {
            var t;
            void 0 === this.itemHeight && (t = (t = this.contentEl.children)[Math.floor(t.length / 2)], this.itemHeight = t.offsetHeight), this.blockHeight = 50 * this.itemHeight, this.clusterRows = 200, this.clusterHeight = 4 * this.blockHeight
        }
    }, {
        key: "getNum", value: function (t) {
            return this.scrollTop = (t ? this.cache : this.scrollEl).scrollTop, Math.floor(this.scrollTop / (this.clusterHeight - this.blockHeight)) || 0
        }
    }, {
        key: "initData", value: function (t, e) {
            if (t.length < 50) return {topOffset: 0, bottomOffset: 0, rowsAbove: 0, rows: t};
            var i = Math.max((this.clusterRows - 50) * e, 0), n = i + this.clusterRows,
                o = Math.max(i * this.itemHeight, 0), s = Math.max((t.length - n) * this.itemHeight, 0), a = [], e = i;
            o < 1 && e++;
            for (var r = i; r < n; r++) t[r] && a.push(t[r]);
            return {topOffset: o, bottomOffset: s, rowsAbove: e, rows: a}
        }
    }, {
        key: "checkChanges", value: function (t, e) {
            var i = e !== this.cache[t];
            return this.cache[t] = e, i
        }
    }, {
        key: "getExtra", value: function (t, e) {
            var i = document.createElement("tr");
            return i.className = "virtual-scroll-".concat(t), e && (i.style.height = "".concat(e, "px")), i.outerHTML
        }
    }]), qo), Wo = (ko(Fo, [{
        key: "init", value: function () {
            this.initConstants(), this.initLocale(), this.initContainer(), this.initTable(), this.initHeader(), this.initData(), this.initHiddenRows(), this.initToolbar(), this.initPagination(), this.initBody(), this.initSearchText(), this.initServer()
        }
    }, {
        key: "initConstants", value: function () {
            var t = this.options;
            this.constants = Po.CONSTANTS, this.constants.theme = N.fn.bootstrapTable.theme;
            var e = t.buttonsPrefix ? "".concat(t.buttonsPrefix, "-") : "";
            this.constants.buttonsClass = [t.buttonsPrefix, e + t.buttonsClass, Bo.sprintf("".concat(e, "%s"), t.iconSize)].join(" ").trim(), this.buttons = Bo.calculateObjectValue(this, t.buttons, [], [])
        }
    }, {
        key: "initLocale", value: function () {
            var t, e;
            this.options.locale && (t = N.fn.bootstrapTable.locales, (e = this.options.locale.split(/-|_/))[0] = e[0].toLowerCase(), e[1] && (e[1] = e[1].toUpperCase()), t[this.options.locale] ? N.extend(this.options, t[this.options.locale]) : t[e.join("-")] ? N.extend(this.options, t[e.join("-")]) : t[e[0]] && N.extend(this.options, t[e[0]]))
        }
    }, {
        key: "initContainer", value: function () {
            var t = ["top", "both"].includes(this.options.paginationVAlign) ? '<div class="fixed-table-pagination clearfix"></div>' : "",
                e = ["bottom", "both"].includes(this.options.paginationVAlign) ? '<div class="fixed-table-pagination"></div>' : "",
                i = Bo.calculateObjectValue(this.options, this.options.loadingTemplate, [this.options.formatLoadingMessage()]);
            this.$container = N('\n      <div class="bootstrap-table '.concat(this.constants.theme, '">\n      <div class="fixed-table-toolbar"></div>\n      ').concat(t, '\n      <div class="fixed-table-container">\n      <div class="fixed-table-header"><table></table></div>\n      <div class="fixed-table-body">\n      <div class="fixed-table-loading">\n      ').concat(i, '\n      </div>\n      </div>\n      <div class="fixed-table-footer"><table><thead><tr></tr></thead></table></div>\n      </div>\n      ').concat(e, "\n      </div>\n    ")), this.$container.insertAfter(this.$el), this.$tableContainer = this.$container.find(".fixed-table-container"), this.$tableHeader = this.$container.find(".fixed-table-header"), this.$tableBody = this.$container.find(".fixed-table-body"), this.$tableLoading = this.$container.find(".fixed-table-loading"), this.$tableFooter = this.$el.find("tfoot"), this.options.buttonsToolbar ? this.$toolbar = N("body").find(this.options.buttonsToolbar) : this.$toolbar = this.$container.find(".fixed-table-toolbar"), this.$pagination = this.$container.find(".fixed-table-pagination"), this.$tableBody.append(this.$el), this.$container.after('<div class="clearfix"></div>'), this.$el.addClass(this.options.classes), this.$tableLoading.addClass(this.options.classes), this.options.height && (this.$tableContainer.addClass("fixed-height"), this.options.showFooter && this.$tableContainer.addClass("has-footer"), this.options.classes.split(" ").includes("table-bordered") && (this.$tableBody.append('<div class="fixed-table-border"></div>'), this.$tableBorder = this.$tableBody.find(".fixed-table-border"), this.$tableLoading.addClass("fixed-table-border")), this.$tableFooter = this.$container.find(".fixed-table-footer"))
        }
    }, {
        key: "initTable", value: function () {
            var t, n = this, o = [];
            this.$header = this.$el.find(">thead"), this.$header.length ? this.options.theadClasses && this.$header.addClass(this.options.theadClasses) : this.$header = N('<thead class="'.concat(this.options.theadClasses, '"></thead>')).appendTo(this.$el), this._headerTrClasses = [], this._headerTrStyles = [], this.$header.find("tr").each(function (t, e) {
                var e = N(e), i = [];
                e.find("th").each(function (t, e) {
                    e = N(e);
                    void 0 !== e.data("field") && e.data("field", "".concat(e.data("field"))), i.push(N.extend({}, {
                        title: e.html(),
                        class: e.attr("class"),
                        titleTooltip: e.attr("title"),
                        rowspan: e.attr("rowspan") ? +e.attr("rowspan") : void 0,
                        colspan: e.attr("colspan") ? +e.attr("colspan") : void 0
                    }, e.data()))
                }), o.push(i), e.attr("class") && n._headerTrClasses.push(e.attr("class")), e.attr("style") && n._headerTrStyles.push(e.attr("style"))
            }), Array.isArray(this.options.columns[0]) || (this.options.columns = [this.options.columns]), this.options.columns = N.extend(!0, [], o, this.options.columns), this.columns = [], this.fieldsColumnsIndex = [], Bo.setFieldIndex(this.options.columns), this.options.columns.forEach(function (t, i) {
                t.forEach(function (t, e) {
                    t = N.extend({}, Fo.COLUMN_DEFAULTS, t);
                    void 0 !== t.fieldIndex && (n.columns[t.fieldIndex] = t, n.fieldsColumnsIndex[t.field] = t.fieldIndex), n.options.columns[i][e] = t
                })
            }), this.options.data.length || (t = Bo.trToData(this.columns, this.$el.find(">tbody>tr"))).length && (this.options.data = t, this.fromHtml = !0), this.options.pagination && "server" !== this.options.sidePagination || (this.footerData = Bo.trToData(this.columns, this.$el.find(">tfoot>tr"))), this.footerData && this.$el.find("tfoot").html("<tr></tr>"), !this.options.showFooter || this.options.cardView ? this.$tableFooter.hide() : this.$tableFooter.show()
        }
    }, {
        key: "initHeader", value: function () {
            var f = this, m = {}, g = [];
            this.header = {
                fields: [],
                styles: [],
                classes: [],
                formatters: [],
                detailFormatters: [],
                events: [],
                sorters: [],
                sortNames: [],
                cellStyles: [],
                searchables: []
            }, Bo.updateFieldGroup(this.options.columns), this.options.columns.forEach(function (t, p) {
                g.push("<tr".concat(Bo.sprintf(' class="%s"', f._headerTrClasses[p]), " ").concat(Bo.sprintf(' style="%s"', f._headerTrStyles[p]), ">"));
                var e = "";
                (e = 0 === p && Bo.hasDetailViewIcon(f.options) ? '<th class="detail" rowspan="'.concat(f.options.columns.length, '">\n          <div class="fht-cell"></div>\n          </th>') : e) && "right" !== f.options.detailViewAlign && g.push(e), t.forEach(function (t, e) {
                    var i = Bo.sprintf(' class="%s"', t.class), n = t.widthUnit, o = parseFloat(t.width),
                        s = Bo.sprintf("text-align: %s; ", t.halign || t.align),
                        a = Bo.sprintf("text-align: %s; ", t.align), r = Bo.sprintf("vertical-align: %s; ", t.valign);
                    if (r += Bo.sprintf("width: %s; ", !t.checkbox && !t.radio || o ? o ? o + n : void 0 : t.showSelectTitle ? void 0 : "36px"), void 0 !== t.fieldIndex || t.visible) {
                        var o = Bo.calculateObjectValue(null, f.options.headerStyle, [t]), l = [], n = "";
                        if (o && o.css) for (var c = 0, d = Object.entries(o.css); c < d.length; c++) {
                            var u = Io(d[c], 2), h = u[0], u = u[1];
                            l.push("".concat(h, ": ").concat(u))
                        }
                        if (o && o.classes && (n = Bo.sprintf(' class="%s"', t.class ? [t.class, o.classes].join(" ") : o.classes)), void 0 !== t.fieldIndex) {
                            if (f.header.fields[t.fieldIndex] = t.field, f.header.styles[t.fieldIndex] = a + r, f.header.classes[t.fieldIndex] = i, f.header.formatters[t.fieldIndex] = t.formatter, f.header.detailFormatters[t.fieldIndex] = t.detailFormatter, f.header.events[t.fieldIndex] = t.events, f.header.sorters[t.fieldIndex] = t.sorter, f.header.sortNames[t.fieldIndex] = t.sortName, f.header.cellStyles[t.fieldIndex] = t.cellStyle, f.header.searchables[t.fieldIndex] = t.searchable, !t.visible) return;
                            if (f.options.cardView && !t.cardVisible) return;
                            m[t.field] = t
                        }
                        g.push("<th".concat(Bo.sprintf(' title="%s"', t.titleTooltip)), t.checkbox || t.radio ? Bo.sprintf(' class="bs-checkbox %s"', t.class || "") : n || i, Bo.sprintf(' style="%s"', s + r + l.join("; ")), Bo.sprintf(' rowspan="%s"', t.rowspan), Bo.sprintf(' colspan="%s"', t.colspan), Bo.sprintf(' data-field="%s"', t.field), 0 === e && 0 < p ? " data-not-first-th" : "", ">"), g.push(Bo.sprintf('<div class="th-inner %s">', f.options.sortable && t.sortable ? "sortable both" : ""));
                        r = f.options.escape ? Bo.escapeHTML(t.title) : t.title, e = r;
                        t.checkbox && (r = "", !f.options.singleSelect && f.options.checkboxHeader && (r = '<label><input name="btSelectAll" type="checkbox" /><span></span></label>'), f.header.stateField = t.field), t.radio && (r = "", f.header.stateField = t.field), !r && t.showSelectTitle && (r += e), g.push(r), g.push("</div>"), g.push('<div class="fht-cell"></div>'), g.push("</div>"), g.push("</th>")
                    }
                }), e && "right" === f.options.detailViewAlign && g.push(e), g.push("</tr>")
            }), this.$header.html(g.join("")), this.$header.find("th[data-field]").each(function (t, e) {
                N(e).data(m[N(e).data("field")])
            }), this.$container.off("click", ".th-inner").on("click", ".th-inner", function (t) {
                var e = N(t.currentTarget);
                if (f.options.detailView && !e.parent().hasClass("bs-checkbox") && e.closest(".bootstrap-table")[0] !== f.$container[0]) return !1;
                f.options.sortable && e.parent().data().sortable && f.onSort(t)
            }), this.$header.children().children().off("keypress").on("keypress", function (t) {
                f.options.sortable && N(t.currentTarget).data().sortable && 13 === (t.keyCode || t.which) && f.onSort(t)
            });
            var t = Bo.getEventName("resize.bootstrap-table", this.$el.attr("id"));
            N(window).off(t), !this.options.showHeader || this.options.cardView ? (this.$header.hide(), this.$tableHeader.hide(), this.$tableLoading.css("top", 0)) : (this.$header.show(), this.$tableHeader.show(), this.$tableLoading.css("top", this.$header.outerHeight() + 1), this.getCaret(), N(window).on(t, function () {
                return f.resetView()
            })), this.$selectAll = this.$header.find('[name="btSelectAll"]'), this.$selectAll.off("click").on("click", function (t) {
                t.stopPropagation();
                t = N(t.currentTarget).prop("checked");
                f[t ? "checkAll" : "uncheckAll"](), f.updateSelected()
            })
        }
    }, {
        key: "initData", value: function (t, e) {
            "append" === e ? this.options.data = this.options.data.concat(t) : "prepend" === e ? this.options.data = [].concat(t).concat(this.options.data) : (t = t || Bo.deepCopy(this.options.data), this.options.data = Array.isArray(t) ? t : t[this.options.dataField]), this.data = Oo(this.options.data), this.options.sortReset && (this.unsortedData = Oo(this.data)), "server" !== this.options.sidePagination && this.initSort()
        }
    }, {
        key: "initSort", value: function () {
            var s = this, a = this.options.sortName, r = "desc" === this.options.sortOrder ? -1 : 1,
                l = this.header.fields.indexOf(this.options.sortName);
            -1 !== l ? (this.options.sortStable && this.data.forEach(function (t, e) {
                t.hasOwnProperty("_position") || (t._position = e)
            }), this.options.customSort ? Bo.calculateObjectValue(this.options, this.options.customSort, [this.options.sortName, this.options.sortOrder, this.data]) : this.data.sort(function (t, e) {
                s.header.sortNames[l] && (a = s.header.sortNames[l]);
                var i = Bo.getItemField(t, a, s.options.escape), n = Bo.getItemField(e, a, s.options.escape),
                    o = Bo.calculateObjectValue(s.header, s.header.sorters[l], [i, n, t, e]);
                return void 0 !== o ? s.options.sortStable && 0 === o ? r * (t._position - e._position) : r * o : Bo.sort(i, n, r, s.options.sortStable, t._position, e._position)
            }), void 0 !== this.options.sortClass && (clearTimeout(0), setTimeout(function () {
                s.$el.removeClass(s.options.sortClass);
                var t = s.$header.find('[data-field="'.concat(s.options.sortName, '"]')).index();
                s.$el.find("tr td:nth-child(".concat(t + 1, ")")).addClass(s.options.sortClass)
            }, 250))) : this.options.sortReset && (this.data = Oo(this.unsortedData))
        }
    }, {
        key: "onSort", value: function (t) {
            var e = t.type, i = t.currentTarget, t = "keypress" === e ? N(i) : N(i).parent(),
                e = this.$header.find("th").eq(t.index());
            if (this.$header.add(this.$header_).find("span.order").remove(), this.options.sortName === t.data("field") ? (void 0 === (i = this.options.sortOrder) ? this.options.sortOrder = "asc" : "asc" === i ? this.options.sortOrder = "desc" : "desc" === this.options.sortOrder && (this.options.sortOrder = this.options.sortReset ? void 0 : "asc"), void 0 === this.options.sortOrder && (this.options.sortName = void 0)) : (this.options.sortName = t.data("field"), this.options.rememberOrder ? this.options.sortOrder = "asc" === t.data("order") ? "desc" : "asc" : this.options.sortOrder = this.columns[this.fieldsColumnsIndex[t.data("field")]].sortOrder || this.columns[this.fieldsColumnsIndex[t.data("field")]].order), this.trigger("sort", this.options.sortName, this.options.sortOrder), t.add(e).data("order", this.options.sortOrder), this.getCaret(), "server" === this.options.sidePagination && this.options.serverSort) return this.options.pageNumber = 1, void this.initServer(this.options.silentSort);
            this.initSort(), this.initBody()
        }
    }, {
        key: "initToolbar", value: function () {
            var a = this, r = this.options, t = [], i = 0, l = 0;
            this.$toolbar.find(".bs-bars").children().length && N("body").append(N(r.toolbar)), this.$toolbar.html(""), "string" != typeof r.toolbar && "object" !== To(r.toolbar) || N(Bo.sprintf('<div class="bs-bars %s-%s"></div>', this.constants.classes.pull, r.toolbarAlign)).appendTo(this.$toolbar).append(N(r.toolbar)), t = ['<div class="'.concat(["columns", "columns-".concat(r.buttonsAlign), this.constants.classes.buttonsGroup, "".concat(this.constants.classes.pull, "-").concat(r.buttonsAlign)].join(" "), '">')], "string" == typeof r.icons && (r.icons = Bo.calculateObjectValue(null, r.icons)), "string" == typeof r.buttonsOrder && (r.buttonsOrder = r.buttonsOrder.replace(/\[|\]| |'/g, "").toLowerCase().split(",")), this.buttons = Object.assign(this.buttons, {
                paginationSwitch: {
                    text: r.pagination ? r.formatPaginationSwitchUp() : r.formatPaginationSwitchDown(),
                    icon: r.pagination ? r.icons.paginationSwitchDown : r.icons.paginationSwitchUp,
                    render: !1,
                    event: this.togglePagination,
                    attributes: {"aria-label": r.formatPaginationSwitch(), title: r.formatPaginationSwitch()}
                },
                refresh: {
                    text: r.formatRefresh(),
                    icon: r.icons.refresh,
                    render: !1,
                    event: this.refresh,
                    attributes: {"aria-label": r.formatRefresh(), title: r.formatRefresh()}
                },
                toggle: {
                    text: r.formatToggle(),
                    icon: r.icons.toggleOff,
                    render: !1,
                    event: this.toggleView,
                    attributes: {"aria-label": r.formatToggleOn(), title: r.formatToggleOn()}
                },
                fullscreen: {
                    text: r.formatFullscreen(),
                    icon: r.icons.fullscreen,
                    render: !1,
                    event: this.toggleFullscreen,
                    attributes: {"aria-label": r.formatFullscreen(), title: r.formatFullscreen()}
                },
                columns: {
                    render: !1, html: function () {
                        var t, o = [];
                        o.push('<div class="keep-open '.concat(a.constants.classes.buttonsDropdown, '" title="').concat(r.formatColumns(), '">\n            <button class="').concat(a.constants.buttonsClass, ' dropdown-toggle" type="button" data-toggle="dropdown"\n            aria-label="Columns" title="').concat(r.formatColumns(), '">\n            ').concat(r.showButtonIcons ? Bo.sprintf(a.constants.html.icon, r.iconsPrefix, r.icons.columns) : "", "\n            ").concat(r.showButtonText ? r.formatColumns() : "", "\n            ").concat(a.constants.html.dropdownCaret, "\n            </button>\n            ").concat(a.constants.html.toolbarDropdown[0])), r.showColumnsSearch && (o.push(Bo.sprintf(a.constants.html.toolbarDropdownItem, Bo.sprintf('<input type="text" class="%s" name="columnsSearch" placeholder="%s" autocomplete="off">', a.constants.classes.input, r.formatSearch()))), o.push(a.constants.html.toolbarDropdownSeparator)), r.showColumnsToggleAll && (t = a.getVisibleColumns().length === a.columns.filter(function (t) {
                            return !a.isSelectionColumn(t)
                        }).length, o.push(Bo.sprintf(a.constants.html.toolbarDropdownItem, Bo.sprintf('<input type="checkbox" class="toggle-all" %s> <span>%s</span>', t ? 'checked="checked"' : "", r.formatColumnsToggleAll()))), o.push(a.constants.html.toolbarDropdownSeparator));
                        var s = 0;
                        return a.columns.forEach(function (t, e) {
                            t.visible && s++
                        }), a.columns.forEach(function (t, e) {
                            var i, n;
                            a.isSelectionColumn(t) || r.cardView && !t.cardVisible || (i = t.visible ? ' checked="checked"' : "", n = s <= r.minimumCountColumns && i ? ' disabled="disabled"' : "", t.switchable && (o.push(Bo.sprintf(a.constants.html.toolbarDropdownItem, Bo.sprintf('<input type="checkbox" data-field="%s" value="%s"%s%s> <span>%s</span>', t.field, e, i, n, t.title))), l++))
                        }), o.push(a.constants.html.toolbarDropdown[1], "</div>"), o.join("")
                    }
                }
            });
            for (var e = {}, n = 0, o = Object.entries(this.buttons); n < o.length; n++) {
                var s = Io(o[n], 2), c = s[0], d = s[1], u = void 0;
                if (d.hasOwnProperty("html")) u = Bo.calculateObjectValue(r, d.html); else {
                    if (u = '<button class="'.concat(this.constants.buttonsClass, '" type="button" name="').concat(c, '"'), d.hasOwnProperty("attributes")) for (var h = 0, p = Object.entries(d.attributes); h < p.length; h++) {
                        var f = Io(p[h], 2), m = f[0], f = f[1];
                        u += " ".concat(m, '="').concat(Bo.calculateObjectValue(r, f), '"')
                    }
                    u += ">", r.showButtonIcons && d.hasOwnProperty("icon") && (g = Bo.calculateObjectValue(r, d.icon), u += Bo.sprintf(this.constants.html.icon, r.iconsPrefix, g) + " "), r.showButtonText && d.hasOwnProperty("text") && (u += Bo.calculateObjectValue(r, d.text)), u += "</button>"
                }
                e[c] = u;
                var s = "show".concat(c.charAt(0).toUpperCase()).concat(c.substring(1)), g = r[s];
                !(!d.hasOwnProperty("render") || d.hasOwnProperty("render") && d.render) || void 0 !== g && !0 !== g || (r[s] = !0), r.buttonsOrder.includes(c) || r.buttonsOrder.push(c)
            }
            var v = !0, b = !1, y = void 0;
            try {
                for (var w, x = r.buttonsOrder[Symbol.iterator](); !(v = (w = x.next()).done); v = !0) {
                    var _ = w.value;
                    r["show".concat(_.charAt(0).toUpperCase()).concat(_.substring(1))] && t.push(e[_])
                }
            } catch (t) {
                b = !0, y = t
            } finally {
                try {
                    v || null == x.return || x.return()
                } finally {
                    if (b) throw y
                }
            }
            t.push("</div>"), (this.showToolbar || 2 < t.length) && this.$toolbar.append(t.join(""));
            for (var C, S, T, $ = 0, E = Object.entries(this.buttons); $ < E.length; $++) {
                var k = Io(E[$], 2), I = k[0], O = k[1];
                if (O.hasOwnProperty("event") && ("function" != typeof O.event && "string" != typeof O.event || "continue" !== function () {
                    var t = "string" == typeof O.event ? window[O.event] : O.event;
                    return a.$toolbar.find('button[name="'.concat(I, '"]')).off("click").on("click", function () {
                        return t.call(a)
                    }), "continue"
                }())) for (var z = 0, A = Object.entries(O.event); z < A.length; z++) !function () {
                    var t = Io(A[z], 2), e = t[0], t = t[1], i = "string" == typeof t ? window[t] : t;
                    a.$toolbar.find('button[name="'.concat(I, '"]')).off(e).on(e, function () {
                        return i.call(a)
                    })
                }()
            }
            r.showColumns && (C = (D = this.$toolbar.find(".keep-open")).find('input[type="checkbox"]:not(".toggle-all")'), S = D.find('input[type="checkbox"].toggle-all'), l <= r.minimumCountColumns && D.find("input").prop("disabled", !0), D.find("li, label").off("click").on("click", function (t) {
                t.stopImmediatePropagation()
            }), C.off("click").on("click", function (t) {
                t = t.currentTarget, t = N(t);
                a._toggleColumn(t.val(), t.prop("checked"), !1), a.trigger("column-switch", t.data("field"), t.prop("checked")), S.prop("checked", C.filter(":checked").length === a.columns.filter(function (t) {
                    return !a.isSelectionColumn(t)
                }).length)
            }), S.off("click").on("click", function (t) {
                t = t.currentTarget;
                a._toggleAllColumns(N(t).prop("checked"))
            }), r.showColumnsSearch && (L = D.find('[name="columnsSearch"]'), T = D.find(".dropdown-item-marker"), L.on("keyup paste change", function (t) {
                var t = t.currentTarget, i = N(t).val().toLowerCase();
                T.show(), C.each(function (t, e) {
                    e = N(e).parents(".dropdown-item-marker");
                    e.text().toLowerCase().includes(i) || e.hide()
                })
            })));

            function P(t) {
                var e = "keyup drop blur mouseup";
                t.off(e).on(e, function (t) {
                    r.searchOnEnterKey && 13 !== t.keyCode || [37, 38, 39, 40].includes(t.keyCode) || (clearTimeout(i), i = setTimeout(function () {
                        a.onSearch({currentTarget: t.currentTarget})
                    }, r.searchTimeOut))
                })
            }

            var D, L, M;
            (r.search || this.showSearchClearButton) && "string" != typeof r.searchSelector ? (t = [], b = Bo.sprintf(this.constants.html.searchButton, this.constants.buttonsClass, r.formatSearch(), r.showButtonIcons ? Bo.sprintf(this.constants.html.icon, r.iconsPrefix, r.icons.search) : "", r.showButtonText ? r.formatSearch() : ""), y = Bo.sprintf(this.constants.html.searchClearButton, this.constants.buttonsClass, r.formatClearSearch(), r.showButtonIcons ? Bo.sprintf(this.constants.html.icon, r.iconsPrefix, r.icons.clearSearch) : "", r.showButtonText ? r.formatClearSearch() : ""), L = D = '<input class="'.concat(this.constants.classes.input, "\n        ").concat(Bo.sprintf(" %s%s", this.constants.classes.inputPrefix, r.iconSize), '\n        search-input" type="search" placeholder="').concat(r.formatSearch(), '" autocomplete="off">'), (r.showSearchButton || r.showSearchClearButton) && (y = (r.showSearchButton ? b : "") + (r.showSearchClearButton ? y : ""), L = r.search ? Bo.sprintf(this.constants.html.inputGroup, D, y) : y), t.push(Bo.sprintf('\n        <div class="'.concat(this.constants.classes.pull, "-").concat(r.searchAlign, " search ").concat(this.constants.classes.inputGroup, '">\n          %s\n        </div>\n      '), L)), this.$toolbar.append(t.join("")), M = Bo.getSearchInput(this), r.showSearchButton ? (this.$toolbar.find(".search button[name=search]").off("click").on("click", function (t) {
                clearTimeout(i), i = setTimeout(function () {
                    a.onSearch({currentTarget: M})
                }, r.searchTimeOut)
            }), r.searchOnEnterKey && P(M)) : P(M), r.showSearchClearButton && this.$toolbar.find(".search button[name=clearSearch]").click(function () {
                a.resetSearch()
            })) : "string" == typeof r.searchSelector && P(Bo.getSearchInput(this))
        }
    }, {
        key: "onSearch", value: function () {
            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {}, e = t.currentTarget,
                i = t.firedByInitSearchText, t = !(1 < arguments.length && void 0 !== arguments[1]) || arguments[1];
            if (void 0 !== e && N(e).length && t) {
                t = N(e).val().trim();
                if (this.options.trimOnSearch && N(e).val() !== t && N(e).val(t), this.searchText === t) return;
                e !== Bo.getSearchInput(this)[0] && !N(e).hasClass("search-input") || (this.searchText = t, this.options.searchText = t)
            }
            i || (this.options.pageNumber = 1), this.initSearch(), i && "client" !== this.options.sidePagination || this.updatePagination(), this.trigger("search", this.searchText)
        }
    }, {
        key: "initSearch", value: function () {
            var u = this;
            if (this.filterOptions = this.filterOptions || this.options.filterOptions, "server" !== this.options.sidePagination) {
                if (this.options.customSearch) return this.data = Bo.calculateObjectValue(this.options, this.options.customSearch, [this.options.data, this.searchText, this.filterColumns]), void (this.options.sortReset && (this.unsortedData = Oo(this.data)));
                var h = this.searchText && (this.fromHtml ? Bo.escapeHTML(this.searchText) : this.searchText).toLowerCase(),
                    a = Bo.isEmptyObject(this.filterColumns) ? null : this.filterColumns;
                "function" == typeof this.filterOptions.filterAlgorithm ? this.data = this.options.data.filter(function (t, e) {
                    return u.filterOptions.filterAlgorithm.apply(null, [t, a])
                }) : "string" == typeof this.filterOptions.filterAlgorithm && (this.data = a ? this.options.data.filter(function (t, e) {
                    var i = u.filterOptions.filterAlgorithm;
                    if ("and" === i) {
                        for (var n in a) if (Array.isArray(a[n]) && !a[n].includes(t[n]) || !Array.isArray(a[n]) && t[n] !== a[n]) return !1
                    } else if ("or" === i) {
                        var o, s = !1;
                        for (o in a) (Array.isArray(a[o]) && a[o].includes(t[o]) || !Array.isArray(a[o]) && t[o] === a[o]) && (s = !0);
                        return s
                    }
                    return !0
                }) : Oo(this.options.data));
                var p = this.getVisibleFields();
                this.data = h ? this.data.filter(function (t, e) {
                    for (var i = 0; i < u.header.fields.length; i++) if (u.header.searchables[i] && (!u.options.visibleSearch || -1 !== p.indexOf(u.header.fields[i]))) {
                        var n = Bo.isNumeric(u.header.fields[i]) ? parseInt(u.header.fields[i], 10) : u.header.fields[i],
                            o = u.columns[u.fieldsColumnsIndex[n]], s = void 0;
                        if ("string" == typeof n) for (var s = t, a = n.split("."), r = 0; r < a.length; r++) null !== s[a[r]] && (s = s[a[r]]); else s = t[n];
                        if (u.options.searchAccentNeutralise && (s = Bo.normalizeAccent(s)), "string" == typeof (s = o && o.searchFormatter ? Bo.calculateObjectValue(o, u.header.formatters[i], [s, t, e, o.field], s) : s) || "number" == typeof s) if (u.options.strictSearch) {
                            if ("".concat(s).toLowerCase() === h) return !0
                        } else {
                            var n = /(?:(<=|=>|=<|>=|>|<)(?:\s+)?(\d+)?|(\d+)?(\s+)?(<=|=>|=<|>=|>|<))/gm.exec(h),
                                l = !1;
                            if (n) {
                                var o = n[1] || "".concat(n[5], "l"), n = n[2] || n[3], c = parseInt(s, 10),
                                    d = parseInt(n, 10);
                                switch (o) {
                                    case">":
                                    case"<l":
                                        l = d < c;
                                        break;
                                    case"<":
                                    case">l":
                                        l = c < d;
                                        break;
                                    case"<=":
                                    case"=<":
                                    case">=l":
                                    case"=>l":
                                        l = c <= d;
                                        break;
                                    case">=":
                                    case"=>":
                                    case"<=l":
                                    case"=<l":
                                        l = d <= c
                                }
                            }
                            if (l || "".concat(s).toLowerCase().includes(h)) return !0
                        }
                    }
                    return !1
                }) : this.data, this.options.sortReset && (this.unsortedData = Oo(this.data)), this.initSort()
            }
        }
    }, {
        key: "initPagination", value: function () {
            var i = this, n = this.options;
            if (n.pagination) {
                this.$pagination.show();
                var t, e, o, s, a = [], r = !1, l = this.getData({includeHiddenRows: !1}),
                    c = (c = "string" == typeof (c = n.pageList) ? c.replace(/\[|\]| /g, "").toLowerCase().split(",") : c).map(function (t) {
                        return "string" == typeof t ? t.toLowerCase() === n.formatAllRows().toLowerCase() || ["all", "unlimited"].includes(t.toLowerCase()) ? n.formatAllRows() : +t : t
                    });
                if (this.paginationParts = n.paginationParts, "string" == typeof this.paginationParts && (this.paginationParts = this.paginationParts.replace(/\[|\]| |'/g, "").split(",")), "server" !== n.sidePagination && (n.totalRows = l.length), this.totalPages = 0, n.totalRows && (n.pageSize === n.formatAllRows() && (n.pageSize = n.totalRows, r = !0), this.totalPages = 1 + ~~((n.totalRows - 1) / n.pageSize), n.totalPages = this.totalPages), 0 < this.totalPages && n.pageNumber > this.totalPages && (n.pageNumber = this.totalPages), this.pageFrom = (n.pageNumber - 1) * n.pageSize + 1, this.pageTo = n.pageNumber * n.pageSize, this.pageTo > n.totalRows && (this.pageTo = n.totalRows), this.options.pagination && "server" !== this.options.sidePagination && (this.options.totalNotFiltered = this.options.data.length), this.options.showExtendedPagination || (this.options.totalNotFiltered = void 0), (this.paginationParts.includes("pageInfo") || this.paginationParts.includes("pageInfoShort") || this.paginationParts.includes("pageSize")) && a.push('<div class="'.concat(this.constants.classes.pull, "-").concat(n.paginationDetailHAlign, ' pagination-detail">')), (this.paginationParts.includes("pageInfo") || this.paginationParts.includes("pageInfoShort")) && (f = this.paginationParts.includes("pageInfoShort") ? n.formatDetailPagination(n.totalRows) : n.formatShowingRows(this.pageFrom, this.pageTo, n.totalRows, n.totalNotFiltered), a.push('<span class="pagination-info">\n      '.concat(f, "\n      </span>"))), this.paginationParts.includes("pageSize") && (a.push('<span class="page-list">'), s = ['<span class="'.concat(this.constants.classes.paginationDropdown, '">\n        <button class="').concat(this.constants.buttonsClass, ' dropdown-toggle" type="button" data-toggle="dropdown">\n        <span class="page-size">\n        ').concat(r ? n.formatAllRows() : n.pageSize, "\n        </span>\n        ").concat(this.constants.html.dropdownCaret, "\n        </button>\n        ").concat(this.constants.html.pageDropdown[0])], c.forEach(function (t, e) {
                    (!n.smartDisplay || 0 === e || c[e - 1] < n.totalRows) && (e = r ? t === n.formatAllRows() ? i.constants.classes.dropdownActive : "" : t === n.pageSize ? i.constants.classes.dropdownActive : "", s.push(Bo.sprintf(i.constants.html.pageDropdownItem, e, t)))
                }), s.push("".concat(this.constants.html.pageDropdown[1], "</span>")), a.push(n.formatRecordsPerPage(s.join("")))), (this.paginationParts.includes("pageInfo") || this.paginationParts.includes("pageInfoShort") || this.paginationParts.includes("pageSize")) && a.push("</span></div>"), this.paginationParts.includes("pageList")) {
                    a.push('<div class="'.concat(this.constants.classes.pull, "-").concat(n.paginationHAlign, ' pagination">'), Bo.sprintf(this.constants.html.pagination[0], Bo.sprintf(" pagination-%s", n.iconSize)), Bo.sprintf(this.constants.html.paginationItem, " page-pre", n.formatSRPaginationPreText(), n.paginationPreText)), e = this.totalPages < n.paginationSuccessivelySize ? (o = 1, this.totalPages) : (o = n.pageNumber - n.paginationPagesBySide) + 2 * n.paginationPagesBySide, n.pageNumber < n.paginationSuccessivelySize - 1 && (e = n.paginationSuccessivelySize), (o = n.paginationSuccessivelySize > this.totalPages - o ? o - (n.paginationSuccessivelySize - (this.totalPages - o)) + 1 : o) < 1 && (o = 1), e > this.totalPages && (e = this.totalPages);
                    var d = Math.round(n.paginationPagesBySide / 2), u = function (t) {
                        return Bo.sprintf(i.constants.html.paginationItem, (1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "") + (t === n.pageNumber ? " ".concat(i.constants.classes.paginationActive) : ""), n.formatSRPaginationPageText(t), t)
                    };
                    if (1 < o) {
                        var h = n.paginationPagesBySide;
                        for (o <= h && (h = o - 1), t = 1; t <= h; t++) a.push(u(t));
                        o - 1 === h + 1 ? a.push(u(t = o - 1)) : h < o - 1 && (o - 2 * n.paginationPagesBySide > n.paginationPagesBySide && n.paginationUseIntermediate ? (t = Math.round((o - d) / 2 + d), a.push(u(t, " page-intermediate"))) : a.push(Bo.sprintf(this.constants.html.paginationItem, " page-first-separator disabled", "", "...")))
                    }
                    for (t = o; t <= e; t++) a.push(u(t));
                    if (this.totalPages > e) {
                        var p = this.totalPages - (n.paginationPagesBySide - 1);
                        for (e + 1 === (p = p <= e ? e + 1 : p) - 1 ? a.push(u(t = e + 1)) : e + 1 < p && (this.totalPages - e > 2 * n.paginationPagesBySide && n.paginationUseIntermediate ? (t = Math.round((this.totalPages - d - e) / 2 + e), a.push(u(t, " page-intermediate"))) : a.push(Bo.sprintf(this.constants.html.paginationItem, " page-last-separator disabled", "", "..."))), t = p; t <= this.totalPages; t++) a.push(u(t))
                    }
                    a.push(Bo.sprintf(this.constants.html.paginationItem, " page-next", n.formatSRPaginationNextText(), n.paginationNextText)), a.push(this.constants.html.pagination[1], "</div>")
                }
                this.$pagination.html(a.join(""));
                var f = ["bottom", "both"].includes(n.paginationVAlign) ? " ".concat(this.constants.classes.dropup) : "";
                this.$pagination.last().find(".page-list > span").addClass(f), n.onlyInfoPagination || (o = this.$pagination.find(".page-list a"), d = this.$pagination.find(".page-pre"), p = this.$pagination.find(".page-next"), f = this.$pagination.find(".page-item").not(".page-next, .page-pre, .page-last-separator, .page-first-separator"), this.totalPages <= 1 && this.$pagination.find("div.pagination").hide(), n.smartDisplay && (c.length < 2 || n.totalRows <= c[0]) && this.$pagination.find("span.page-list").hide(), this.$pagination[this.getData().length ? "show" : "hide"](), n.paginationLoop || (1 === n.pageNumber && d.addClass("disabled"), n.pageNumber === this.totalPages && p.addClass("disabled")), r && (n.pageSize = n.formatAllRows()), o.off("click").on("click", function (t) {
                    return i.onPageListChange(t)
                }), d.off("click").on("click", function (t) {
                    return i.onPagePre(t)
                }), p.off("click").on("click", function (t) {
                    return i.onPageNext(t)
                }), f.off("click").on("click", function (t) {
                    return i.onPageNumber(t)
                }))
            } else this.$pagination.hide()
        }
    }, {
        key: "updatePagination", value: function (t) {
            t && N(t.currentTarget).hasClass("disabled") || (this.options.maintainMetaData || this.resetRows(), this.initPagination(), this.trigger("page-change", this.options.pageNumber, this.options.pageSize), "server" === this.options.sidePagination ? this.initServer() : this.initBody())
        }
    }, {
        key: "onPageListChange", value: function (t) {
            t.preventDefault();
            var e = N(t.currentTarget);
            return e.parent().addClass(this.constants.classes.dropdownActive).siblings().removeClass(this.constants.classes.dropdownActive), this.options.pageSize = e.text().toUpperCase() === this.options.formatAllRows().toUpperCase() ? this.options.formatAllRows() : +e.text(), this.$toolbar.find(".page-size").text(this.options.pageSize), this.updatePagination(t), !1
        }
    }, {
        key: "onPagePre", value: function (t) {
            return t.preventDefault(), this.options.pageNumber - 1 == 0 ? this.options.pageNumber = this.options.totalPages : this.options.pageNumber--, this.updatePagination(t), !1
        }
    }, {
        key: "onPageNext", value: function (t) {
            return t.preventDefault(), this.options.pageNumber + 1 > this.options.totalPages ? this.options.pageNumber = 1 : this.options.pageNumber++, this.updatePagination(t), !1
        }
    }, {
        key: "onPageNumber", value: function (t) {
            if (t.preventDefault(), this.options.pageNumber !== +N(t.currentTarget).text()) return this.options.pageNumber = +N(t.currentTarget).text(), this.updatePagination(t), !1
        }
    }, {
        key: "initRow", value: function (T, $, t, e) {
            var E = this, k = [], i = {}, I = [], n = "", o = {}, s = [];
            if (!(-1 < Bo.findIndex(this.hiddenRows, T))) {
                if ((i = Bo.calculateObjectValue(this.options, this.options.rowStyle, [T, $], i)) && i.css) for (var a = 0, r = Object.entries(i.css); a < r.length; a++) {
                    var l = Io(r[a], 2), c = l[0], l = l[1];
                    I.push("".concat(c, ": ").concat(l))
                }
                if (o = Bo.calculateObjectValue(this.options, this.options.rowAttributes, [T, $], o)) for (var d = 0, u = Object.entries(o); d < u.length; d++) {
                    var h = Io(u[d], 2), p = h[0], h = h[1];
                    s.push("".concat(p, '="').concat(Bo.escapeHTML(h), '"'))
                }
                if (T._data && !Bo.isEmptyObject(T._data)) for (var f = 0, m = Object.entries(T._data); f < m.length; f++) {
                    var g = Io(m[f], 2), v = g[0], g = g[1];
                    if ("index" === v) return;
                    n += " data-".concat(v, "='").concat("object" === To(g) ? JSON.stringify(g) : g, "'")
                }
                k.push("<tr", Bo.sprintf(" %s", s.length ? s.join(" ") : void 0), Bo.sprintf(' id="%s"', Array.isArray(T) ? void 0 : T._id), Bo.sprintf(' class="%s"', i.classes || (Array.isArray(T) ? void 0 : T._class)), Bo.sprintf(' style="%s"', Array.isArray(T) ? void 0 : T._style), ' data-index="'.concat($, '"'), Bo.sprintf(' data-uniqueid="%s"', Bo.getItemField(T, this.options.uniqueId, !1)), Bo.sprintf(' data-has-detail-view="%s"', this.options.detailView && Bo.calculateObjectValue(null, this.options.detailFilter, [$, T]) ? "true" : void 0), Bo.sprintf("%s", n), ">"), this.options.cardView && k.push('<td colspan="'.concat(this.header.fields.length, '"><div class="card-views">'));
                i = "";
                return Bo.hasDetailViewIcon(this.options) && (i = "<td>", Bo.calculateObjectValue(null, this.options.detailFilter, [$, T]) && (i += '\n          <a class="detail-icon" href="#">\n          '.concat(Bo.sprintf(this.constants.html.icon, this.options.iconsPrefix, this.options.icons.detailOpen), "\n          </a>\n        ")), i += "</td>"), i && "right" !== this.options.detailViewAlign && k.push(i), this.header.fields.forEach(function (t, e) {
                    var i = "", n = Bo.getItemField(T, t, E.options.escape), o = "", s = "", a = {}, r = "",
                        l = E.header.classes[e], c = "", d = "", u = "", h = "", p = "", f = "", m = E.columns[e];
                    if ((!E.fromHtml && !E.autoMergeCells || void 0 !== n || m.checkbox || m.radio) && m.visible && (!E.options.cardView || m.cardVisible)) {
                        if (m.escape && (n = Bo.escapeHTML(n)), I.concat([E.header.styles[e]]).length && (d += "".concat(I.concat([E.header.styles[e]]).join("; "))), T["_".concat(t, "_style")] && (d += "".concat(T["_".concat(t, "_style")])), d && (c = ' style="'.concat(d, '"')), T["_".concat(t, "_id")] && (r = Bo.sprintf(' id="%s"', T["_".concat(t, "_id")])), T["_".concat(t, "_class")] && (l = Bo.sprintf(' class="%s"', T["_".concat(t, "_class")])), T["_".concat(t, "_rowspan")] && (h = Bo.sprintf(' rowspan="%s"', T["_".concat(t, "_rowspan")])), T["_".concat(t, "_colspan")] && (p = Bo.sprintf(' colspan="%s"', T["_".concat(t, "_colspan")])), T["_".concat(t, "_title")] && (f = Bo.sprintf(' title="%s"', T["_".concat(t, "_title")])), (a = Bo.calculateObjectValue(E.header, E.header.cellStyles[e], [n, T, $, t], a)).classes && (l = ' class="'.concat(a.classes, '"')), a.css) {
                            for (var g = [], v = 0, b = Object.entries(a.css); v < b.length; v++) {
                                var y = Io(b[v], 2), w = y[0], y = y[1];
                                g.push("".concat(w, ": ").concat(y))
                            }
                            c = ' style="'.concat(g.concat(E.header.styles[e]).join("; "), '"')
                        }
                        if (o = Bo.calculateObjectValue(m, E.header.formatters[e], [n, T, $, t], n), "" !== E.searchText && E.options.searchHighlight && (o = Bo.calculateObjectValue(m, m.searchHighlightFormatter, [o, E.searchText], o.replace(new RegExp("(" + E.searchText + ")", "gim"), "<mark>$1</mark>"))), T["_".concat(t, "_data")] && !Bo.isEmptyObject(T["_".concat(t, "_data")])) for (var x = 0, _ = Object.entries(T["_".concat(t, "_data")]); x < _.length; x++) {
                            var C = Io(_[x], 2), S = C[0], C = C[1];
                            if ("index" === S) return;
                            u += " data-".concat(S, '="').concat(C, '"')
                        }
                        m.checkbox || m.radio ? (s = m.checkbox ? "checkbox" : s, s = m.radio ? "radio" : s, d = m.class || "", a = Bo.isObject(o) && o.hasOwnProperty("checked") ? o.checked : (!0 === o || n) && !1 !== o, m = !m.checkboxEnabled || o && o.disabled, i = [E.options.cardView ? '<div class="card-view '.concat(d, '">') : '<td class="bs-checkbox '.concat(d, '"').concat(l).concat(c, ">"), '<label>\n            <input\n            data-index="'.concat($, '"\n            name="').concat(E.options.selectItemName, '"\n            type="').concat(s, '"\n            ').concat(Bo.sprintf('value="%s"', T[E.options.idField]), "\n            ").concat(Bo.sprintf('checked="%s"', a ? "checked" : void 0), "\n            ").concat(Bo.sprintf('disabled="%s"', m ? "disabled" : void 0), " />\n            <span></span>\n            </label>"), E.header.formatters[e] && "string" == typeof o ? o : "", E.options.cardView ? "</div>" : "</td>"].join(""), T[E.header.stateField] = !0 === o || !!n || o && o.checked) : (o = null == o ? E.options.undefinedText : o, E.options.cardView ? (t = E.options.showHeader ? '<span class="card-view-title"'.concat(c, ">").concat(Bo.getFieldTitle(E.columns, t), "</span>") : "", i = '<div class="card-view">'.concat(t, '<span class="card-view-value">').concat(o, "</span></div>"), E.options.smartDisplay && "" === o && (i = '<div class="card-view"></div>')) : i = "<td".concat(r).concat(l).concat(c).concat(u).concat(h).concat(p).concat(f, ">").concat(o, "</td>")), k.push(i)
                    }
                }), i && "right" === this.options.detailViewAlign && k.push(i), this.options.cardView && k.push("</div></td>"), k.push("</tr>"), k.join("")
            }
        }
    }, {
        key: "initBody", value: function (t) {
            var e = this, i = this.getData();
            this.trigger("pre-body", i), this.$body = this.$el.find(">tbody"), this.$body.length || (this.$body = N("<tbody></tbody>").appendTo(this.$el)), this.options.pagination && "server" !== this.options.sidePagination || (this.pageFrom = 1, this.pageTo = i.length);
            var n = [], o = N(document.createDocumentFragment()), s = !1;
            this.autoMergeCells = Bo.checkAutoMergeCells(i.slice(this.pageFrom - 1, this.pageTo));
            for (var a = this.pageFrom - 1; a < this.pageTo; a++) {
                var r = i[a], r = this.initRow(r, a, i, o), s = s || !!r;
                r && "string" == typeof r && (this.options.virtualScroll ? n.push(r) : o.append(r))
            }
            s ? this.options.virtualScroll ? (this.virtualScroll && this.virtualScroll.destroy(), this.virtualScroll = new Ro({
                rows: n,
                fixedScroll: t,
                scrollEl: this.$tableBody[0],
                contentEl: this.$body[0],
                itemHeight: this.options.virtualScrollItemHeight,
                callback: function () {
                    e.fitHeader(), e.initBodyEvent()
                }
            })) : this.$body.html(o) : this.$body.html('<tr class="no-records-found">'.concat(Bo.sprintf('<td colspan="%s">%s</td>', this.getVisibleFields().length + Bo.getDetailViewIndexOffset(this.options), this.options.formatNoMatches()), "</tr>")), t || this.scrollTo(0), this.initBodyEvent(), this.updateSelected(), this.initFooter(), this.resetView(), "server" !== this.options.sidePagination && (this.options.totalRows = i.length), this.trigger("post-body", i)
        }
    }, {
        key: "initBodyEvent", value: function () {
            var c = this;
            this.$body.find("> tr[data-index] > td").off("click dblclick").on("click dblclick", function (t) {
                var e, i = N(t.currentTarget), n = i.parent(), o = N(t.target).parents(".card-views").children(),
                    s = N(t.target).parents(".card-view"), a = n.data("index"), r = c.data[a],
                    l = c.options.cardView ? o.index(s) : i[0].cellIndex,
                    o = c.getVisibleFields()[l - Bo.getDetailViewIndexOffset(c.options)],
                    s = c.columns[c.fieldsColumnsIndex[o]], l = Bo.getItemField(r, o, c.options.escape);
                i.find(".detail-icon").length || (c.trigger("click" === t.type ? "click-cell" : "dbl-click-cell", o, l, r, i), c.trigger("click" === t.type ? "click-row" : "dbl-click-row", r, n, o), "click" === t.type && c.options.clickToSelect && s.clickToSelect && !Bo.calculateObjectValue(c.options, c.options.ignoreClickToSelectOn, [t.target]) && (e = n.find(Bo.sprintf('[name="%s"]', c.options.selectItemName))).length && e[0].click(), "click" === t.type && c.options.detailViewByClick && c.toggleDetailView(a, c.header.detailFormatters[c.fieldsColumnsIndex[o]]))
            }).off("mousedown").on("mousedown", function (t) {
                c.multipleSelectRowCtrlKey = t.ctrlKey || t.metaKey, c.multipleSelectRowShiftKey = t.shiftKey
            }), this.$body.find("> tr[data-index] > td > .detail-icon").off("click").on("click", function (t) {
                return t.preventDefault(), c.toggleDetailView(N(t.currentTarget).parent().parent().data("index")), !1
            }), this.$selectItem = this.$body.find(Bo.sprintf('[name="%s"]', this.options.selectItemName)), this.$selectItem.off("click").on("click", function (t) {
                t.stopImmediatePropagation();
                t = N(t.currentTarget);
                c._toggleCheck(t.prop("checked"), t.data("index"))
            }), this.header.events.forEach(function (t, e) {
                var i = t;
                if (i) {
                    "string" == typeof i && (i = Bo.calculateObjectValue(null, i));
                    var r = c.header.fields[e], l = c.getVisibleFields().indexOf(r);
                    if (-1 !== l) {
                        l += Bo.getDetailViewIndexOffset(c.options);
                        for (var n in i) !function (s) {
                            if (!i.hasOwnProperty(s)) return;
                            var a = i[s];
                            c.$body.find(">tr:not(.no-records-found)").each(function (t, e) {
                                var o = N(e), i = o.find(c.options.cardView ? ".card-views>.card-view" : ">td").eq(l),
                                    n = s.indexOf(" "), e = s.substring(0, n), n = s.substring(n + 1);
                                i.find(n).off(e).on(e, function (t) {
                                    var e = o.data("index"), i = c.data[e], n = i[r];
                                    a.apply(c, [t, n, i, e])
                                })
                            })
                        }(n)
                    }
                }
            })
        }
    }, {
        key: "initServer", value: function (n, t, e) {
            var o = this, i = {}, s = this.header.fields.indexOf(this.options.sortName),
                a = {searchText: this.searchText, sortName: this.options.sortName, sortOrder: this.options.sortOrder};
            if (this.header.sortNames[s] && (a.sortName = this.header.sortNames[s]), this.options.pagination && "server" === this.options.sidePagination && (a.pageSize = this.options.pageSize === this.options.formatAllRows() ? this.options.totalRows : this.options.pageSize, a.pageNumber = this.options.pageNumber), e || this.options.url || this.options.ajax) {
                if ("limit" === this.options.queryParamsType && (a = {
                    search: a.searchText,
                    sort: a.sortName,
                    order: a.sortOrder
                }, this.options.pagination && "server" === this.options.sidePagination && (a.offset = this.options.pageSize === this.options.formatAllRows() ? 0 : this.options.pageSize * (this.options.pageNumber - 1), a.limit = this.options.pageSize === this.options.formatAllRows() ? this.options.totalRows : this.options.pageSize, 0 === a.limit && delete a.limit)), this.options.search && "server" === this.options.sidePagination && this.columns.filter(function (t) {
                    return !t.searchable
                }).length) {
                    var r = !0, l = !(a.searchable = []), s = void 0;
                    try {
                        for (var c, d = this.columns[Symbol.iterator](); !(r = (c = d.next()).done); r = !0) {
                            var u = c.value;
                            !u.checkbox && u.searchable && (this.options.visibleSearch && u.visible || !this.options.visibleSearch) && a.searchable.push(u.field)
                        }
                    } catch (t) {
                        l = !0, s = t
                    } finally {
                        try {
                            r || null == d.return || d.return()
                        } finally {
                            if (l) throw s
                        }
                    }
                }
                if (Bo.isEmptyObject(this.filterColumnsPartial) || (a.filter = JSON.stringify(this.filterColumnsPartial, null)), N.extend(a, t || {}), !1 !== (i = Bo.calculateObjectValue(this.options, this.options.queryParams, [a], i))) {
                    n || this.showLoading();
                    e = N.extend({}, Bo.calculateObjectValue(null, this.options.ajaxOptions), {
                        type: this.options.method,
                        url: e || this.options.url,
                        data: "application/json" === this.options.contentType && "post" === this.options.method ? JSON.stringify(i) : i,
                        cache: this.options.cache,
                        contentType: this.options.contentType,
                        dataType: this.options.dataType,
                        success: function (t, e, i) {
                            t = Bo.calculateObjectValue(o.options, o.options.responseHandler, [t, i], t);
                            o.load(t), o.trigger("load-success", t, i && i.status, i), n || o.hideLoading(), "server" === o.options.sidePagination && 0 < t[o.options.totalField] && !t[o.options.dataField].length && o.updatePagination()
                        },
                        error: function (t) {
                            var e = [];
                            "server" === o.options.sidePagination && ((e = {})[o.options.totalField] = 0, e[o.options.dataField] = []), o.load(e), o.trigger("load-error", t && t.status, t), n || o.$tableLoading.hide()
                        }
                    });
                    return this.options.ajax ? Bo.calculateObjectValue(this, this.options.ajax, [e], null) : (this._xhr && 4 !== this._xhr.readyState && this._xhr.abort(), this._xhr = N.ajax(e)), i
                }
            }
        }
    }, {
        key: "initSearchText", value: function () {
            var t;
            this.options.search && (this.searchText = "") !== this.options.searchText && ((t = Bo.getSearchInput(this)).val(this.options.searchText), this.onSearch({
                currentTarget: t,
                firedByInitSearchText: !0
            }))
        }
    }, {
        key: "getCaret", value: function () {
            var i = this;
            this.$header.find("th").each(function (t, e) {
                N(e).find(".sortable").removeClass("desc asc").addClass(N(e).data("field") === i.options.sortName ? i.options.sortOrder : "both")
            })
        }
    }, {
        key: "updateSelected", value: function () {
            var t = this.$selectItem.filter(":enabled").length && this.$selectItem.filter(":enabled").length === this.$selectItem.filter(":enabled").filter(":checked").length;
            this.$selectAll.add(this.$selectAll_).prop("checked", t), this.$selectItem.each(function (t, e) {
                N(e).closest("tr")[N(e).prop("checked") ? "addClass" : "removeClass"]("selected")
            })
        }
    }, {
        key: "updateRows", value: function () {
            var i = this;
            this.$selectItem.each(function (t, e) {
                i.data[N(e).data("index")][i.header.stateField] = N(e).prop("checked")
            })
        }
    }, {
        key: "resetRows", value: function () {
            var t = !0, e = !1, i = void 0;
            try {
                for (var n, o = this.data[Symbol.iterator](); !(t = (n = o.next()).done); t = !0) {
                    var s = n.value;
                    this.$selectAll.prop("checked", !1), this.$selectItem.prop("checked", !1), this.header.stateField && (s[this.header.stateField] = !1)
                }
            } catch (t) {
                e = !0, i = t
            } finally {
                try {
                    t || null == o.return || o.return()
                } finally {
                    if (e) throw i
                }
            }
            this.initHiddenRows()
        }
    }, {
        key: "trigger", value: function (t) {
            for (var e = "".concat(t, ".bs.table"), i = arguments.length, n = new Array(1 < i ? i - 1 : 0), o = 1; o < i; o++) n[o - 1] = arguments[o];
            (t = this.options)[Fo.EVENTS[e]].apply(t, [].concat(n, [this])), this.$el.trigger(N.Event(e, {sender: this}), n), (t = this.options).onAll.apply(t, [e].concat([].concat(n, [this]))), this.$el.trigger(N.Event("all.bs.table", {sender: this}), [e, n])
        }
    }, {
        key: "resetHeader", value: function () {
            var t = this;
            clearTimeout(this.timeoutId_), this.timeoutId_ = setTimeout(function () {
                return t.fitHeader()
            }, this.$el.is(":hidden") ? 100 : 0)
        }
    }, {
        key: "fitHeader", value: function () {
            var o = this;
            if (this.$el.is(":hidden")) this.timeoutId_ = setTimeout(function () {
                return o.fitHeader()
            }, 100); else {
                var t = this.$tableBody.get(0),
                    e = t.scrollWidth > t.clientWidth && t.scrollHeight > t.clientHeight + this.$header.outerHeight() ? Bo.getScrollBarWidth() : 0;
                this.$el.css("margin-top", -this.$header.outerHeight());
                var t = N(":focus");
                0 < t.length && (0 < (t = t.parents("th")).length && (void 0 === (t = t.attr("data-field")) || 0 < (t = this.$header.find("[data-field='".concat(t, "']"))).length && t.find(":input").addClass("focus-temp"))), this.$header_ = this.$header.clone(!0, !0), this.$selectAll_ = this.$header_.find('[name="btSelectAll"]'), this.$tableHeader.css("margin-right", e).find("table").css("width", this.$el.outerWidth()).html("").attr("class", this.$el.attr("class")).append(this.$header_), this.$tableLoading.css("width", this.$el.outerWidth());
                e = N(".focus-temp:visible:eq(0)");
                0 < e.length && (e.focus(), this.$header.find(".focus-temp").removeClass("focus-temp")), this.$header.find("th[data-field]").each(function (t, e) {
                    o.$header_.find(Bo.sprintf('th[data-field="%s"]', N(e).data("field"))).data(N(e).data())
                });
                for (var s = this.getVisibleFields(), a = this.$header_.find("th"), i = this.$body.find(">tr:not(.no-records-found,.virtual-scroll-top)").eq(0); i.length && i.find('>td[colspan]:not([colspan="1"])').length;) i = i.next();
                var r = i.find("> *").length;
                i.find("> *").each(function (t, e) {
                    var i, n = N(e);
                    Bo.hasDetailViewIcon(o.options) && (0 === t && "right" !== o.options.detailViewAlign || t === r - 1 && "right" === o.options.detailViewAlign) ? (i = (e = a.filter(".detail")).innerWidth() - e.find(".fht-cell").width(), e.find(".fht-cell").width(n.innerWidth() - i)) : (i = t - Bo.getDetailViewIndexOffset(o.options), i = (t = 1 < (t = o.$header_.find(Bo.sprintf('th[data-field="%s"]', s[i]))).length ? N(a[n[0].cellIndex]) : t).innerWidth() - t.find(".fht-cell").width(), t.find(".fht-cell").width(n.innerWidth() - i))
                }), this.horizontalScroll(), this.trigger("post-header")
            }
        }
    }, {
        key: "initFooter", value: function () {
            if (this.options.showFooter && !this.options.cardView) {
                var t = this.getData(), e = [], i = "";
                (i = Bo.hasDetailViewIcon(this.options) ? '<th class="detail"><div class="th-inner"></div><div class="fht-cell"></div></th>' : i) && "right" !== this.options.detailViewAlign && e.push(i);
                var n = !0, o = !1, s = void 0;
                try {
                    for (var a, r = this.columns[Symbol.iterator](); !(n = (a = r.next()).done); n = !0) {
                        var l, c, d = a.value, u = [], h = {}, p = Bo.sprintf(' class="%s"', d.class);
                        if (d.visible && (!(this.footerData && 0 < this.footerData.length) || d.field in this.footerData[0])) {
                            if (this.options.cardView && !d.cardVisible) return;
                            if (l = Bo.sprintf("text-align: %s; ", d.falign || d.align), c = Bo.sprintf("vertical-align: %s; ", d.valign), (h = Bo.calculateObjectValue(null, this.options.footerStyle, [d])) && h.css) for (var f = 0, m = Object.entries(h.css); f < m.length; f++) {
                                var g = Io(m[f], 2), v = g[0], b = g[1];
                                u.push("".concat(v, ": ").concat(b))
                            }
                            h && h.classes && (p = Bo.sprintf(' class="%s"', d.class ? [d.class, h.classes].join(" ") : h.classes)), e.push("<th", p, Bo.sprintf(' style="%s"', l + c + u.concat().join("; ")));
                            var y = 0;
                            (y = this.footerData && 0 < this.footerData.length ? this.footerData[0]["_" + d.field + "_colspan"] || 0 : y) && e.push(' colspan="'.concat(y, '" ')), e.push(">"), e.push('<div class="th-inner">');
                            var w = "";
                            this.footerData && 0 < this.footerData.length && (w = this.footerData[0][d.field] || ""), e.push(Bo.calculateObjectValue(d, d.footerFormatter, [t, w], w)), e.push("</div>"), e.push('<div class="fht-cell"></div>'), e.push("</div>"), e.push("</th>")
                        }
                    }
                } catch (t) {
                    o = !0, s = t
                } finally {
                    try {
                        n || null == r.return || r.return()
                    } finally {
                        if (o) throw s
                    }
                }
                i && "right" === this.options.detailViewAlign && e.push(i), this.options.height || this.$tableFooter.length || (this.$el.append("<tfoot><tr></tr></tfoot>"), this.$tableFooter = this.$el.find("tfoot")), this.$tableFooter.find("tr").html(e.join("")), this.trigger("post-footer", this.$tableFooter)
            }
        }
    }, {
        key: "fitFooter", value: function () {
            var o = this;
            if (this.$el.is(":hidden")) setTimeout(function () {
                return o.fitFooter()
            }, 100); else {
                var t = this.$tableBody.get(0),
                    t = t.scrollWidth > t.clientWidth && t.scrollHeight > t.clientHeight + this.$header.outerHeight() ? Bo.getScrollBarWidth() : 0;
                this.$tableFooter.css("margin-right", t).find("table").css("width", this.$el.outerWidth()).attr("class", this.$el.attr("class")), this.getVisibleFields();
                var s = this.$tableFooter.find("th"), e = this.$body.find(">tr:first-child:not(.no-records-found)");
                for (s.find(".fht-cell").width("auto"); e.length && e.find('>td[colspan]:not([colspan="1"])').length;) e = e.next();
                var a = e.find("> *").length;
                e.find("> *").each(function (t, e) {
                    var i, n = N(e);
                    Bo.hasDetailViewIcon(o.options) && (0 === t && "left" === o.options.detailViewAlign || t === a - 1 && "right" === o.options.detailViewAlign) ? (i = (e = s.filter(".detail")).innerWidth() - e.find(".fht-cell").width(), e.find(".fht-cell").width(n.innerWidth() - i)) : (t = (i = s.eq(t)).innerWidth() - i.find(".fht-cell").width(), i.find(".fht-cell").width(n.innerWidth() - t))
                }), this.horizontalScroll()
            }
        }
    }, {
        key: "horizontalScroll", value: function () {
            var e = this;
            this.$tableBody.off("scroll").on("scroll", function () {
                var t = e.$tableBody.scrollLeft();
                e.options.showHeader && e.options.height && e.$tableHeader.scrollLeft(t), e.options.showFooter && !e.options.cardView && e.$tableFooter.scrollLeft(t), e.trigger("scroll-body", e.$tableBody)
            })
        }
    }, {
        key: "getVisibleFields", value: function () {
            var t = [], e = !0, i = !1, n = void 0;
            try {
                for (var o, s = this.header.fields[Symbol.iterator](); !(e = (o = s.next()).done); e = !0) {
                    var a = o.value, r = this.columns[this.fieldsColumnsIndex[a]];
                    r && r.visible && t.push(a)
                }
            } catch (t) {
                i = !0, n = t
            } finally {
                try {
                    e || null == s.return || s.return()
                } finally {
                    if (i) throw n
                }
            }
            return t
        }
    }, {
        key: "initHiddenRows", value: function () {
            this.hiddenRows = []
        }
    }, {
        key: "getOptions", value: function () {
            var t = N.extend({}, this.options);
            return delete t.data, N.extend(!0, {}, t)
        }
    }, {
        key: "refreshOptions", value: function (t) {
            Bo.compareObjects(this.options, t, !0) || (this.options = N.extend(this.options, t), this.trigger("refresh-options", this.options), this.destroy(), this.init())
        }
    }, {
        key: "getData", value: function (t) {
            var e, a = this, i = this.options.data;
            return !this.searchText && !this.options.customSearch && void 0 === this.options.sortName && Bo.isEmptyObject(this.filterColumns) && Bo.isEmptyObject(this.filterColumnsPartial) || t && t.unfiltered || (i = this.data), t && t.useCurrentPage && (i = i.slice(this.pageFrom - 1, this.pageTo)), t && !t.includeHiddenRows && (e = this.getHiddenRows(), i = i.filter(function (t) {
                return -1 === Bo.findIndex(e, t)
            })), t && t.formatted && i.forEach(function (t) {
                for (var e = 0, i = Object.entries(t); e < i.length; e++) {
                    var n = Io(i[e], 2), o = n[0], s = n[1], n = a.columns[a.fieldsColumnsIndex[o]];
                    if (!n) return;
                    t[o] = Bo.calculateObjectValue(n, a.header.formatters[n.fieldIndex], [s, t, t.index, n.field], s)
                }
            }), i
        }
    }, {
        key: "getSelections", value: function () {
            var e = this;
            return this.options.data.filter(function (t) {
                return !0 === t[e.header.stateField]
            })
        }
    }, {
        key: "load", value: function (t) {
            var e = t;
            this.options.pagination && "server" === this.options.sidePagination && (this.options.totalRows = e[this.options.totalField], this.options.totalNotFiltered = e[this.options.totalNotFilteredField], this.footerData = e[this.options.footerField] ? [e[this.options.footerField]] : void 0), t = e.fixedScroll, e = Array.isArray(e) ? e : e[this.options.dataField], this.initData(e), this.initSearch(), this.initPagination(), this.initBody(t)
        }
    }, {
        key: "append", value: function (t) {
            this.initData(t, "append"), this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0)
        }
    }, {
        key: "prepend", value: function (t) {
            this.initData(t, "prepend"), this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0)
        }
    }, {
        key: "remove", value: function (t) {
            var e, i, n = this.options.data.length;
            if (t.hasOwnProperty("field") && t.hasOwnProperty("values")) {
                for (e = n - 1; 0 <= e; e--) ((i = this.options.data[e]).hasOwnProperty(t.field) || "$index" === t.field) && (i.hasOwnProperty(t.field) || "$index" !== t.field ? t.values.includes(i[t.field]) : t.values.includes(e)) && (this.options.data.splice(e, 1), "server" === this.options.sidePagination && --this.options.totalRows);
                n !== this.options.data.length && (this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0))
            }
        }
    }, {
        key: "removeAll", value: function () {
            0 < this.options.data.length && (this.options.data.splice(0, this.options.data.length), this.initSearch(), this.initPagination(), this.initBody(!0))
        }
    }, {
        key: "insertRow", value: function (t) {
            t.hasOwnProperty("index") && t.hasOwnProperty("row") && (this.options.data.splice(t.index, 0, t.row), this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0))
        }
    }, {
        key: "updateRow", value: function (e) {
            var t = Array.isArray(e) ? e : [e], i = !0, n = !1, e = void 0;
            try {
                for (var o, s = t[Symbol.iterator](); !(i = (o = s.next()).done); i = !0) {
                    var a = o.value;
                    a.hasOwnProperty("index") && a.hasOwnProperty("row") && (a.hasOwnProperty("replace") && a.replace ? this.options.data[a.index] = a.row : N.extend(this.options.data[a.index], a.row))
                }
            } catch (t) {
                n = !0, e = t
            } finally {
                try {
                    i || null == s.return || s.return()
                } finally {
                    if (n) throw e
                }
            }
            this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0)
        }
    }, {
        key: "getRowByUniqueId", value: function (t) {
            for (var e, i, n = this.options.uniqueId, o = t, s = null, a = this.options.data.length - 1; 0 <= a; a--) {
                if ((e = this.options.data[a]).hasOwnProperty(n)) i = e[n]; else {
                    if (!e._data || !e._data.hasOwnProperty(n)) continue;
                    i = e._data[n]
                }
                if ("string" == typeof i ? o = o.toString() : "number" == typeof i && (Number(i) === i && i % 1 == 0 ? o = parseInt(o) : i === Number(i) && 0 !== i && (o = parseFloat(o))), i === o) {
                    s = e;
                    break
                }
            }
            return s
        }
    }, {
        key: "updateByUniqueId", value: function (e) {
            var t = Array.isArray(e) ? e : [e], i = !0, n = !1, e = void 0;
            try {
                for (var o, s = t[Symbol.iterator](); !(i = (o = s.next()).done); i = !0) {
                    var a, r = o.value;
                    r.hasOwnProperty("id") && r.hasOwnProperty("row") && (-1 !== (a = this.options.data.indexOf(this.getRowByUniqueId(r.id))) && (r.hasOwnProperty("replace") && r.replace ? this.options.data[a] = r.row : N.extend(this.options.data[a], r.row)))
                }
            } catch (t) {
                n = !0, e = t
            } finally {
                try {
                    i || null == s.return || s.return()
                } finally {
                    if (n) throw e
                }
            }
            this.initSearch(), this.initPagination(), this.initSort(), this.initBody(!0)
        }
    }, {
        key: "removeByUniqueId", value: function (t) {
            var e = this.options.data.length, t = this.getRowByUniqueId(t);
            t && this.options.data.splice(this.options.data.indexOf(t), 1), e !== this.options.data.length && (this.initSearch(), this.initPagination(), this.initBody(!0))
        }
    }, {
        key: "updateCell", value: function (t) {
            t.hasOwnProperty("index") && t.hasOwnProperty("field") && t.hasOwnProperty("value") && (this.data[t.index][t.field] = t.value, !1 !== t.reinit && (this.initSort(), this.initBody(!0)))
        }
    }, {
        key: "updateCellByUniqueId", value: function (t) {
            var n = this;
            (Array.isArray(t) ? t : [t]).forEach(function (t) {
                var e = t.id, i = t.field, t = t.value, e = n.options.data.indexOf(n.getRowByUniqueId(e));
                -1 !== e && (n.options.data[e][i] = t)
            }), !1 !== t.reinit && (this.initSort(), this.initBody(!0))
        }
    }, {
        key: "showRow", value: function (t) {
            this._toggleRow(t, !0)
        }
    }, {
        key: "hideRow", value: function (t) {
            this._toggleRow(t, !1)
        }
    }, {
        key: "_toggleRow", value: function (t, e) {
            var i;
            t.hasOwnProperty("index") ? i = this.getData()[t.index] : t.hasOwnProperty("uniqueId") && (i = this.getRowByUniqueId(t.uniqueId)), i && (t = Bo.findIndex(this.hiddenRows, i), e || -1 !== t ? e && -1 < t && this.hiddenRows.splice(t, 1) : this.hiddenRows.push(i), this.initBody(!0), this.initPagination())
        }
    }, {
        key: "getHiddenRows", value: function (t) {
            if (t) return this.initHiddenRows(), this.initBody(!0), void this.initPagination();
            var e = this.getData(), i = [], n = !0, o = !1, s = void 0;
            try {
                for (var a, r = e[Symbol.iterator](); !(n = (a = r.next()).done); n = !0) {
                    var l = a.value;
                    this.hiddenRows.includes(l) && i.push(l)
                }
            } catch (t) {
                o = !0, s = t
            } finally {
                try {
                    n || null == r.return || r.return()
                } finally {
                    if (o) throw s
                }
            }
            return this.hiddenRows = i
        }
    }, {
        key: "showColumn", value: function (t) {
            var e = this;
            (Array.isArray(t) ? t : [t]).forEach(function (t) {
                e._toggleColumn(e.fieldsColumnsIndex[t], !0, !0)
            })
        }
    }, {
        key: "hideColumn", value: function (t) {
            var e = this;
            (Array.isArray(t) ? t : [t]).forEach(function (t) {
                e._toggleColumn(e.fieldsColumnsIndex[t], !1, !0)
            })
        }
    }, {
        key: "_toggleColumn", value: function (t, e, i) {
            var n;
            -1 !== t && this.columns[t].visible !== e && (this.columns[t].visible = e, this.initHeader(), this.initSearch(), this.initPagination(), this.initBody(), this.options.showColumns) && (n = this.$toolbar.find('.keep-open input:not(".toggle-all")').prop("disabled", !1), i && n.filter(Bo.sprintf('[value="%s"]', t)).prop("checked", e), n.filter(":checked").length <= this.options.minimumCountColumns && n.filter(":checked").prop("disabled", !0))
        }
    }, {
        key: "getVisibleColumns", value: function () {
            var e = this;
            return this.columns.filter(function (t) {
                return t.visible && !e.isSelectionColumn(t)
            })
        }
    }, {
        key: "getHiddenColumns", value: function () {
            return this.columns.filter(function (t) {
                return !t.visible
            })
        }
    }, {
        key: "isSelectionColumn", value: function (t) {
            return t.radio || t.checkbox
        }
    }, {
        key: "showAllColumns", value: function () {
            this._toggleAllColumns(!0)
        }
    }, {
        key: "hideAllColumns", value: function () {
            this._toggleAllColumns(!1)
        }
    }, {
        key: "_toggleAllColumns", value: function (e) {
            var i, n = this, t = !0, o = !1, s = void 0;
            try {
                for (var a, r = this.columns.slice().reverse()[Symbol.iterator](); !(t = (a = r.next()).done); t = !0) {
                    var l = a.value;
                    l.switchable && (!e && this.options.showColumns && this.getVisibleColumns().length === this.options.minimumCountColumns || (l.visible = e))
                }
            } catch (t) {
                o = !0, s = t
            } finally {
                try {
                    t || null == r.return || r.return()
                } finally {
                    if (o) throw s
                }
            }
            this.initHeader(), this.initSearch(), this.initPagination(), this.initBody(), this.options.showColumns && (i = this.$toolbar.find('.keep-open input[type="checkbox"]:not(".toggle-all")').prop("disabled", !1), e ? i.prop("checked", e) : i.get().reverse().forEach(function (t) {
                i.filter(":checked").length > n.options.minimumCountColumns && N(t).prop("checked", e)
            }), i.filter(":checked").length <= this.options.minimumCountColumns && i.filter(":checked").prop("disabled", !0))
        }
    }, {
        key: "mergeCells", value: function (t) {
            var e, i, n = t.index, o = this.getVisibleFields().indexOf(t.field), s = t.rowspan || 1, a = t.colspan || 1,
                r = this.$body.find(">tr");
            o += Bo.getDetailViewIndexOffset(this.options);
            t = r.eq(n).find(">td").eq(o);
            if (!(n < 0 || o < 0 || n >= this.data.length)) {
                for (e = n; e < n + s; e++) for (i = o; i < o + a; i++) r.eq(e).find(">td").eq(i).hide();
                t.attr("rowspan", s).attr("colspan", a).show()
            }
        }
    }, {
        key: "checkAll", value: function () {
            this._toggleCheckAll(!0)
        }
    }, {
        key: "uncheckAll", value: function () {
            this._toggleCheckAll(!1)
        }
    }, {
        key: "_toggleCheckAll", value: function (t) {
            var e = this.getSelections();
            this.$selectAll.add(this.$selectAll_).prop("checked", t), this.$selectItem.filter(":enabled").prop("checked", t), this.updateRows(), this.updateSelected();
            var i = this.getSelections();
            t ? this.trigger("check-all", i, e) : this.trigger("uncheck-all", i, e)
        }
    }, {
        key: "checkInvert", value: function () {
            var t = this.$selectItem.filter(":enabled"), e = t.filter(":checked");
            t.each(function (t, e) {
                N(e).prop("checked", !N(e).prop("checked"))
            }), this.updateRows(), this.updateSelected(), this.trigger("uncheck-some", e), e = this.getSelections(), this.trigger("check-some", e)
        }
    }, {
        key: "check", value: function (t) {
            this._toggleCheck(!0, t)
        }
    }, {
        key: "uncheck", value: function (t) {
            this._toggleCheck(!1, t)
        }
    }, {
        key: "_toggleCheck", value: function (t, e) {
            var i = this.$selectItem.filter('[data-index="'.concat(e, '"]')), n = this.options.data[e];
            if (i.is(":radio") || this.options.singleSelect || this.options.multipleSelectRow && !this.multipleSelectRowCtrlKey && !this.multipleSelectRowShiftKey) {
                var o = !0, s = !1, a = void 0;
                try {
                    for (var r, l = this.options.data[Symbol.iterator](); !(o = (r = l.next()).done); o = !0) r.value[this.header.stateField] = !1
                } catch (t) {
                    s = !0, a = t
                } finally {
                    try {
                        o || null == l.return || l.return()
                    } finally {
                        if (s) throw a
                    }
                }
                this.$selectItem.filter(":checked").not(i).prop("checked", !1)
            }
            if (n[this.header.stateField] = t, this.options.multipleSelectRow) {
                if (this.multipleSelectRowShiftKey && 0 <= this.multipleSelectRowLastSelectedIndex) for (var c = [this.multipleSelectRowLastSelectedIndex, e].sort(), d = c[0] + 1; d < c[1]; d++) this.data[d][this.header.stateField] = !0, this.$selectItem.filter('[data-index="'.concat(d, '"]')).prop("checked", !0);
                this.multipleSelectRowCtrlKey = !1, this.multipleSelectRowShiftKey = !1, this.multipleSelectRowLastSelectedIndex = t ? e : -1
            }
            i.prop("checked", t), this.updateSelected(), this.trigger(t ? "check" : "uncheck", this.data[e], i)
        }
    }, {
        key: "checkBy", value: function (t) {
            this._toggleCheckBy(!0, t)
        }
    }, {
        key: "uncheckBy", value: function (t) {
            this._toggleCheckBy(!1, t)
        }
    }, {
        key: "_toggleCheckBy", value: function (i, n) {
            var o, s = this;
            n.hasOwnProperty("field") && n.hasOwnProperty("values") && (o = [], this.data.forEach(function (t, e) {
                return !!t.hasOwnProperty(n.field) && void (n.values.includes(t[n.field]) && (e = s.$selectItem.filter(":enabled").filter(Bo.sprintf('[data-index="%s"]', e)), (e = i ? e.not(":checked") : e.filter(":checked")).length && (e.prop("checked", i), t[s.header.stateField] = i, o.push(t), s.trigger(i ? "check" : "uncheck", t, e))))
            }), this.updateSelected(), this.trigger(i ? "check-some" : "uncheck-some", o))
        }
    }, {
        key: "refresh", value: function (t) {
            t && t.url && (this.options.url = t.url), t && t.pageNumber && (this.options.pageNumber = t.pageNumber), t && t.pageSize && (this.options.pageSize = t.pageSize), this.trigger("refresh", this.initServer(t && t.silent, t && t.query, t && t.url))
        }
    }, {
        key: "destroy", value: function () {
            this.$el.insertBefore(this.$container), N(this.options.toolbar).insertBefore(this.$el), this.$container.next().remove(), this.$container.remove(), this.$el.html(this.$el_.html()).css("margin-top", "0").attr("class", this.$el_.attr("class") || "")
        }
    }, {
        key: "resetView", value: function (t) {
            var e, i, n = 0;
            t && t.height && (this.options.height = t.height), this.$selectAll.prop("checked", 0 < this.$selectItem.length && this.$selectItem.length === this.$selectItem.filter(":checked").length), this.$tableContainer.toggleClass("has-card-view", this.options.cardView), !this.options.cardView && this.options.showHeader && this.options.height ? (this.$tableHeader.show(), this.resetHeader(), n += this.$header.outerHeight(!0) + 1) : (this.$tableHeader.hide(), this.trigger("post-header")), !this.options.cardView && this.options.showFooter && (this.$tableFooter.show(), this.fitFooter(), this.options.height && (n += this.$tableFooter.outerHeight(!0))), this.$container.hasClass("fullscreen") ? (this.$tableContainer.css("height", ""), this.$tableContainer.css("width", "")) : this.options.height && (this.$tableBorder && (this.$tableBorder.css("width", ""), this.$tableBorder.css("height", "")), e = this.$toolbar.outerHeight(!0), i = this.$pagination.outerHeight(!0), t = this.options.height - e - i, i = (e = this.$tableBody.find(">table")).outerHeight(), this.$tableContainer.css("height", "".concat(t, "px")), this.$tableBorder && e.is(":visible") && (i = t - i - 2, this.$tableBody[0].scrollWidth - this.$tableBody.innerWidth() && (i -= Bo.getScrollBarWidth()), this.$tableBorder.css("width", "".concat(e.outerWidth(), "px")), this.$tableBorder.css("height", "".concat(i, "px")))), this.options.cardView ? (this.$el.css("margin-top", "0"), this.$tableContainer.css("padding-bottom", "0"), this.$tableFooter.hide()) : (this.getCaret(), this.$tableContainer.css("padding-bottom", "".concat(n, "px"))), this.trigger("reset-view")
        }
    }, {
        key: "showLoading", value: function () {
            this.$tableLoading.toggleClass("open", !0);
            var t = this.options.loadingFontSize;
            "auto" === this.options.loadingFontSize && (t = .04 * this.$tableLoading.width(), t = Math.max(12, t), t = Math.min(32, t), t = "".concat(t, "px")), this.$tableLoading.find(".loading-text").css("font-size", t)
        }
    }, {
        key: "hideLoading", value: function () {
            this.$tableLoading.toggleClass("open", !1)
        }
    }, {
        key: "togglePagination", value: function () {
            this.options.pagination = !this.options.pagination;
            var t = this.options.showButtonIcons ? this.options.pagination ? this.options.icons.paginationSwitchDown : this.options.icons.paginationSwitchUp : "",
                e = this.options.showButtonText ? this.options.pagination ? this.options.formatPaginationSwitchUp() : this.options.formatPaginationSwitchDown() : "";
            this.$toolbar.find('button[name="paginationSwitch"]').html(Bo.sprintf(this.constants.html.icon, this.options.iconsPrefix, t) + " " + e), this.updatePagination()
        }
    }, {
        key: "toggleFullscreen", value: function () {
            this.$el.closest(".bootstrap-table").toggleClass("fullscreen"), this.resetView()
        }
    }, {
        key: "toggleView", value: function () {
            this.options.cardView = !this.options.cardView, this.initHeader();
            var t = this.options.showButtonIcons ? this.options.cardView ? this.options.icons.toggleOn : this.options.icons.toggleOff : "",
                e = this.options.showButtonText ? this.options.cardView ? this.options.formatToggleOff() : this.options.formatToggleOn() : "";
            this.$toolbar.find('button[name="toggle"]').html(Bo.sprintf(this.constants.html.icon, this.options.iconsPrefix, t) + " " + e), this.initBody(), this.trigger("toggle", this.options.cardView)
        }
    }, {
        key: "resetSearch", value: function (t) {
            var e = Bo.getSearchInput(this);
            e.val(t || ""), this.onSearch({currentTarget: e})
        }
    }, {
        key: "filterBy", value: function (t, e) {
            this.filterOptions = Bo.isEmptyObject(e) ? this.options.filterOptions : N.extend(this.options.filterOptions, e), this.filterColumns = Bo.isEmptyObject(t) ? {} : t, this.options.pageNumber = 1, this.initSearch(), this.updatePagination()
        }
    }, {
        key: "scrollTo", value: function (t) {
            var e = {unit: "px", value: 0};
            "object" === To(t) ? e = Object.assign(e, t) : "string" == typeof t && "bottom" === t ? e.value = this.$tableBody[0].scrollHeight : "string" != typeof t && "number" != typeof t || (e.value = t);
            var i = e.value;
            "rows" === e.unit && (i = 0, this.$body.find("> tr:lt(".concat(e.value, ")")).each(function (t, e) {
                i += N(e).outerHeight(!0)
            })), this.$tableBody.scrollTop(i)
        }
    }, {
        key: "getScrollPosition", value: function () {
            return this.$tableBody.scrollTop()
        }
    }, {
        key: "selectPage", value: function (t) {
            0 < t && t <= this.options.totalPages && (this.options.pageNumber = t, this.updatePagination())
        }
    }, {
        key: "prevPage", value: function () {
            1 < this.options.pageNumber && (this.options.pageNumber--, this.updatePagination())
        }
    }, {
        key: "nextPage", value: function () {
            this.options.pageNumber < this.options.totalPages && (this.options.pageNumber++, this.updatePagination())
        }
    }, {
        key: "toggleDetailView", value: function (t, e) {
            this.$body.find(Bo.sprintf('> tr[data-index="%s"]', t)).next().is("tr.detail-view") ? this.collapseRow(t) : this.expandRow(t, e), this.resetView()
        }
    }, {
        key: "expandRow", value: function (t, e) {
            var i = this.data[t], n = this.$body.find(Bo.sprintf('> tr[data-index="%s"][data-has-detail-view]', t));
            n.next().is("tr.detail-view") || (this.options.detailViewIcon && n.find("a.detail-icon").html(Bo.sprintf(this.constants.html.icon, this.options.iconsPrefix, this.options.icons.detailClose)), n.after(Bo.sprintf('<tr class="detail-view"><td colspan="%s"></td></tr>', n.children("td").length)), n = n.next().find("td"), e = e || this.options.detailFormatter, e = Bo.calculateObjectValue(this.options, e, [t, i, n], ""), 1 === n.length && n.append(e), this.trigger("expand-row", t, i, n))
        }
    }, {
        key: "expandRowByUniqueId", value: function (t) {
            t = this.getRowByUniqueId(t);
            t && this.expandRow(this.data.indexOf(t))
        }
    }, {
        key: "collapseRow", value: function (t) {
            var e = this.data[t], i = this.$body.find(Bo.sprintf('> tr[data-index="%s"][data-has-detail-view]', t));
            i.next().is("tr.detail-view") && (this.options.detailViewIcon && i.find("a.detail-icon").html(Bo.sprintf(this.constants.html.icon, this.options.iconsPrefix, this.options.icons.detailOpen)), this.trigger("collapse-row", t, e, i.next()), i.next().remove())
        }
    }, {
        key: "collapseRowByUniqueId", value: function (t) {
            t = this.getRowByUniqueId(t);
            t && this.collapseRow(this.data.indexOf(t))
        }
    }, {
        key: "expandAllRows", value: function () {
            for (var t = this.$body.find("> tr[data-index][data-has-detail-view]"), e = 0; e < t.length; e++) this.expandRow(N(t[e]).data("index"))
        }
    }, {
        key: "collapseAllRows", value: function () {
            for (var t = this.$body.find("> tr[data-index][data-has-detail-view]"), e = 0; e < t.length; e++) this.collapseRow(N(t[e]).data("index"))
        }
    }, {
        key: "updateColumnTitle", value: function (i) {
            i.hasOwnProperty("field") && i.hasOwnProperty("title") && (this.columns[this.fieldsColumnsIndex[i.field]].title = this.options.escape ? Bo.escapeHTML(i.title) : i.title, this.columns[this.fieldsColumnsIndex[i.field]].visible && (void 0 !== this.options.height ? this.$tableHeader : this.$header).find("th[data-field]").each(function (t, e) {
                if (N(e).data("field") === i.field) return N(N(e).find(".th-inner")[0]).text(i.title), !1
            }))
        }
    }, {
        key: "updateFormatText", value: function (t, e) {
            /^format/.test(t) && this.options[t] && ("string" == typeof e ? this.options[t] = function () {
                return e
            } : "function" == typeof e && (this.options[t] = e), this.initToolbar(), this.initPagination(), this.initBody())
        }
    }]), Fo);

    function Fo(t, e) {
        $o(this, Fo), this.options = e, this.$el = N(t), this.$el_ = this.$el.clone(), this.timeoutId_ = 0, this.timeoutFooter_ = 0
    }

    function qo(t) {
        var e = this;
        $o(this, qo), this.rows = t.rows, this.scrollEl = t.scrollEl, this.contentEl = t.contentEl, this.callback = t.callback, this.itemHeight = t.itemHeight, this.cache = {}, this.scrollTop = this.scrollEl.scrollTop, this.initDOM(this.rows, t.fixedScroll), this.scrollEl.scrollTop = this.scrollTop, this.lastCluster = 0;

        function i() {
            e.lastCluster !== (e.lastCluster = e.getNum()) && (e.initDOM(e.rows), e.callback())
        }

        this.scrollEl.addEventListener("scroll", i, !1), this.destroy = function () {
            e.contentEl.innerHtml = "", e.scrollEl.removeEventListener("scroll", i, !1)
        }
    }

    return Wo.VERSION = Po.VERSION, Wo.DEFAULTS = Po.DEFAULTS, Wo.LOCALES = Po.LOCALES, Wo.COLUMN_DEFAULTS = Po.COLUMN_DEFAULTS, Wo.METHODS = Po.METHODS, Wo.EVENTS = Po.EVENTS, N.BootstrapTable = Wo, N.fn.bootstrapTable = function (o) {
        for (var s, t = arguments.length, a = new Array(1 < t ? t - 1 : 0), e = 1; e < t; e++) a[e - 1] = arguments[e];
        return this.each(function (t, e) {
            var i = N(e).data("bootstrap.table"), n = N.extend({}, Wo.DEFAULTS, N(e).data(), "object" === To(o) && o);
            if ("string" == typeof o) {
                if (!Po.METHODS.includes(o)) throw new Error("Unknown method: ".concat(o));
                if (!i) return;
                s = i[o].apply(i, a), "destroy" === o && N(e).removeData("bootstrap.table")
            }
            i || (i = new N.BootstrapTable(e, n), N(e).data("bootstrap.table", i), i.init())
        }), void 0 === s ? this : s
    }, N.fn.bootstrapTable.Constructor = Wo, N.fn.bootstrapTable.theme = Po.THEME, N.fn.bootstrapTable.VERSION = Po.VERSION, N.fn.bootstrapTable.defaults = Wo.DEFAULTS, N.fn.bootstrapTable.columnDefaults = Wo.COLUMN_DEFAULTS, N.fn.bootstrapTable.events = Wo.EVENTS, N.fn.bootstrapTable.locales = Wo.LOCALES, N.fn.bootstrapTable.methods = Wo.METHODS, N.fn.bootstrapTable.utils = Bo, N(function () {
        N('[data-toggle="table"]').bootstrapTable()
    }), Wo
}), function () {
    "use strict";

    function t(p) {
        p.fn._fadeIn = p.fn.fadeIn;
        var f = p.noop || function () {
            }, m = /MSIE/.test(navigator.userAgent),
            g = /MSIE 6.0/.test(navigator.userAgent) && !/MSIE 8.0/.test(navigator.userAgent),
            v = (document.documentMode, p.isFunction(document.createElement("div").style.setExpression));
        p.blockUI = function (t) {
            i(window, t)
        }, p.unblockUI = function (t) {
            w(window, t)
        }, p.growlUI = function (t, e, i, n) {
            var o = p('<div class="growlUI"></div>');
            t && o.append("<h1>" + t + "</h1>"), e && o.append("<h2>" + e + "</h2>"), void 0 === i && (i = 3e3);

            function s(t) {
                p.blockUI({
                    message: o,
                    fadeIn: void 0 !== (t = t || {}).fadeIn ? t.fadeIn : 700,
                    fadeOut: void 0 !== t.fadeOut ? t.fadeOut : 1e3,
                    timeout: void 0 !== t.timeout ? t.timeout : i,
                    centerY: !1,
                    showOverlay: !1,
                    onUnblock: n,
                    css: p.blockUI.defaults.growlCSS
                })
            }

            s();
            o.css("opacity");
            o.mouseover(function () {
                s({fadeIn: 0, timeout: 3e4});
                var t = p(".blockMsg");
                t.stop(), t.fadeTo(300, 1)
            }).mouseout(function () {
                p(".blockMsg").fadeOut(1e3)
            })
        }, p.fn.block = function (t) {
            if (this[0] === window) return p.blockUI(t), this;
            var e = p.extend({}, p.blockUI.defaults, t || {});
            return this.each(function () {
                var t = p(this);
                e.ignoreIfBlocked && t.data("blockUI.isBlocked") || t.unblock({fadeOut: 0})
            }), this.each(function () {
                "static" == p.css(this, "position") && (this.style.position = "relative", p(this).data("blockUI.static", !0)), this.style.zoom = 1, i(this, t)
            })
        }, p.fn.unblock = function (t) {
            return this[0] === window ? (p.unblockUI(t), this) : this.each(function () {
                w(this, t)
            })
        }, p.blockUI.version = 2.7, p.blockUI.defaults = {
            message: "<h1>Please wait...</h1>",
            title: null,
            draggable: !0,
            theme: !1,
            css: {
                padding: 0,
                margin: 0,
                width: "30%",
                top: "40%",
                left: "35%",
                textAlign: "center",
                color: "#000",
                border: "3px solid #aaa",
                backgroundColor: "#fff",
                cursor: "wait"
            },
            themedCSS: {width: "30%", top: "40%", left: "35%"},
            overlayCSS: {backgroundColor: "#000", opacity: .6, cursor: "wait"},
            cursorReset: "default",
            growlCSS: {
                width: "350px",
                top: "10px",
                left: "",
                right: "10px",
                border: "none",
                padding: "5px",
                opacity: .6,
                cursor: "default",
                color: "#fff",
                backgroundColor: "#000",
                "-webkit-border-radius": "10px",
                "-moz-border-radius": "10px",
                "border-radius": "10px"
            },
            iframeSrc: /^https/i.test(window.location.href || "") ? "javascript:false" : "about:blank",
            forceIframe: !1,
            baseZ: 1e3,
            centerX: !0,
            centerY: !0,
            allowBodyStretch: !0,
            bindEvents: !0,
            constrainTabKey: !0,
            fadeIn: 200,
            fadeOut: 400,
            timeout: 0,
            showOverlay: !0,
            focusInput: !0,
            focusableElements: ":input:enabled:visible",
            onBlock: null,
            onUnblock: null,
            onOverlayClick: null,
            quirksmodeOffsetHack: 4,
            blockMsgClass: "blockMsg",
            ignoreIfBlocked: !1
        };
        var b = null, y = [];

        function i(t, i) {
            var e, n, o, s, a, r, l, c, d, u = t == window, h = i && void 0 !== i.message ? i.message : void 0;
            (i = p.extend({}, p.blockUI.defaults, i || {})).ignoreIfBlocked && p(t).data("blockUI.isBlocked") || (i.overlayCSS = p.extend({}, p.blockUI.defaults.overlayCSS, i.overlayCSS || {}), o = p.extend({}, p.blockUI.defaults.css, i.css || {}), i.onOverlayClick && (i.overlayCSS.cursor = "pointer"), s = p.extend({}, p.blockUI.defaults.themedCSS, i.themedCSS || {}), h = void 0 === h ? i.message : h, u && b && w(window, {fadeOut: 0}), h && "string" != typeof h && (h.parentNode || h.jquery) && (e = h.jquery ? h[0] : h, l = {}, p(t).data("blockUI.history", l), l.el = e, l.parent = e.parentNode, l.display = e.style.display, l.position = e.style.position, l.parent && l.parent.removeChild(e)), p(t).data("blockUI.onUnblock", i.onUnblock), d = i.baseZ, l = m || i.forceIframe ? p('<iframe class="blockUI" style="z-index:' + d++ + ';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="' + i.iframeSrc + '"></iframe>') : p('<div class="blockUI" style="display:none"></div>'), e = i.theme ? p('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:' + d++ + ';display:none"></div>') : p('<div class="blockUI blockOverlay" style="z-index:' + d++ + ';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>'), i.theme && u ? (c = '<div class="blockUI ' + i.blockMsgClass + ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:' + (d + 10) + ';display:none;position:fixed">', i.title && (c += '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">' + (i.title || "&nbsp;") + "</div>"), c += '<div class="ui-widget-content ui-dialog-content"></div>', c += "</div>") : i.theme ? (c = '<div class="blockUI ' + i.blockMsgClass + ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:' + (d + 10) + ';display:none;position:absolute">', i.title && (c += '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">' + (i.title || "&nbsp;") + "</div>"), c += '<div class="ui-widget-content ui-dialog-content"></div>', c += "</div>") : c = u ? '<div class="blockUI ' + i.blockMsgClass + ' blockPage" style="z-index:' + (d + 10) + ';display:none;position:fixed"></div>' : '<div class="blockUI ' + i.blockMsgClass + ' blockElement" style="z-index:' + (d + 10) + ';display:none;position:absolute"></div>', d = p(c), h && (i.theme ? (d.css(s), d.addClass("ui-widget-content")) : d.css(o)), i.theme || e.css(i.overlayCSS), e.css("position", u ? "fixed" : "absolute"), (m || i.forceIframe) && l.css("opacity", 0), c = [l, e, d], n = p(u ? "body" : t), p.each(c, function () {
                this.appendTo(n)
            }), i.theme && i.draggable && p.fn.draggable && d.draggable({
                handle: ".ui-dialog-titlebar",
                cancel: "li"
            }), s = v && (!p.support.boxModel || 0 < p("object,embed", u ? null : t).length), (g || s) && (u && i.allowBodyStretch && p.support.boxModel && p("html,body").css("height", "100%"), !g && p.support.boxModel || u || (o = C(t, "borderTopWidth"), s = C(t, "borderLeftWidth"), a = o ? "(0 - " + o + ")" : 0, r = s ? "(0 - " + s + ")" : 0), p.each(c, function (t, e) {
                e = e[0].style;
                e.position = "absolute", t < 2 ? (u ? e.setExpression("height", "Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:" + i.quirksmodeOffsetHack + ') + "px"') : e.setExpression("height", 'this.parentNode.offsetHeight + "px"'), u ? e.setExpression("width", 'jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"') : e.setExpression("width", 'this.parentNode.offsetWidth + "px"'), r && e.setExpression("left", r), a && e.setExpression("top", a)) : i.centerY ? (u && e.setExpression("top", '(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"'), e.marginTop = 0) : !i.centerY && u && (t = i.css && i.css.top ? parseInt(i.css.top, 10) : 0, e.setExpression("top", "((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + " + t + ') + "px"'))
            })), h && ((i.theme ? d.find(".ui-widget-content") : d).append(h), (h.jquery || h.nodeType) && p(h).show()), (m || i.forceIframe) && i.showOverlay && l.show(), i.fadeIn ? (c = i.onBlock || f, l = i.showOverlay && !h ? c : f, c = h ? c : f, i.showOverlay && e._fadeIn(i.fadeIn, l), h && d._fadeIn(i.fadeIn, c)) : (i.showOverlay && e.show(), h && d.show(), i.onBlock && i.onBlock.bind(d)()), x(1, t, i), u ? (b = d[0], y = p(i.focusableElements, b), i.focusInput && setTimeout(_, 20)) : function (t, e, i) {
                var n = t.parentNode, o = t.style, s = (n.offsetWidth - t.offsetWidth) / 2 - C(n, "borderLeftWidth"),
                    n = (n.offsetHeight - t.offsetHeight) / 2 - C(n, "borderTopWidth");
                e && (o.left = 0 < s ? s + "px" : "0");
                i && (o.top = 0 < n ? n + "px" : "0")
            }(d[0], i.centerX, i.centerY), i.timeout && (d = setTimeout(function () {
                u ? p.unblockUI(i) : p(t).unblock(i)
            }, i.timeout), p(t).data("blockUI.timeout", d)))
        }

        function w(t, e) {
            var i, n, o = t == window, s = p(t), a = s.data("blockUI.history"), r = s.data("blockUI.timeout");
            r && (clearTimeout(r), s.removeData("blockUI.timeout")), e = p.extend({}, p.blockUI.defaults, e || {}), x(0, t, e), null === e.onUnblock && (e.onUnblock = s.data("blockUI.onUnblock"), s.removeData("blockUI.onUnblock")), n = o ? p("body").children().filter(".blockUI").add("body > .blockUI") : s.find(">.blockUI"), e.cursorReset && (1 < n.length && (n[1].style.cursor = e.cursorReset), 2 < n.length && (n[2].style.cursor = e.cursorReset)), o && (b = y = null), e.fadeOut ? (i = n.length, n.stop().fadeOut(e.fadeOut, function () {
                0 == --i && l(n, a, e, t)
            })) : l(n, a, e, t)
        }

        function l(t, e, i, n) {
            var o = p(n);
            o.data("blockUI.isBlocked") || (t.each(function (t, e) {
                this.parentNode && this.parentNode.removeChild(this)
            }), e && e.el && (e.el.style.display = e.display, e.el.style.position = e.position, e.el.style.cursor = "default", e.parent && e.parent.appendChild(e.el), o.removeData("blockUI.history")), o.data("blockUI.static") && o.css("position", "static"), "function" == typeof i.onUnblock && i.onUnblock(n, i), n = (o = p(document.body)).width(), i = o[0].style.width, o.width(n - 1).width(n), o[0].style.width = i)
        }

        function x(t, e, i) {
            var n = e == window, e = p(e);
            !t && (n && !b || !n && !e.data("blockUI.isBlocked")) || (e.data("blockUI.isBlocked", t), n && i.bindEvents && (!t || i.showOverlay) && (n = "mousedown mouseup keydown keypress keyup touchstart touchend touchmove", t ? p(document).bind(n, i, o) : p(document).unbind(n, o)))
        }

        function o(t) {
            if ("keydown" === t.type && t.keyCode && 9 == t.keyCode && b && t.data.constrainTabKey) {
                var e = y, i = !t.shiftKey && t.target === e[e.length - 1], n = t.shiftKey && t.target === e[0];
                if (i || n) return setTimeout(function () {
                    _(n)
                }, 10), !1
            }
            e = t.data, i = p(t.target);
            return i.hasClass("blockOverlay") && e.onOverlayClick && e.onOverlayClick(t), 0 < i.parents("div." + e.blockMsgClass).length || 0 === i.parents().children().filter("div.blockUI").length
        }

        function _(t) {
            !y || (t = y[!0 === t ? y.length - 1 : 0]) && t.focus()
        }

        function C(t, e) {
            return parseInt(p.css(t, e), 10) || 0
        }
    }

    "function" == typeof define && define.amd && define.amd.jQuery ? define(["jquery"], t) : t(jQuery)
}(), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : (t = t || self).Sweetalert2 = e()
}(this, function () {
    "use strict";

    function c(t) {
        return (c = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    function n(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    function o(t, e) {
        for (var i = 0; i < e.length; i++) {
            var n = e[i];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
        }
    }

    function t(t, e, i) {
        return e && o(t.prototype, e), i && o(t, i), t
    }

    function l() {
        return (l = Object.assign || function (t) {
            for (var e = 1; e < arguments.length; e++) {
                var i, n = arguments[e];
                for (i in n) Object.prototype.hasOwnProperty.call(n, i) && (t[i] = n[i])
            }
            return t
        }).apply(this, arguments)
    }

    function s(t) {
        return (s = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
            return t.__proto__ || Object.getPrototypeOf(t)
        })(t)
    }

    function a(t, e) {
        return (a = Object.setPrototypeOf || function (t, e) {
            return t.__proto__ = e, t
        })(t, e)
    }

    function r(t, e, i) {
        return (r = function () {
            if ("undefined" != typeof Reflect && Reflect.construct && !Reflect.construct.sham) {
                if ("function" == typeof Proxy) return 1;
                try {
                    return Date.prototype.toString.call(Reflect.construct(Date, [], function () {
                    })), 1
                } catch (t) {
                    return
                }
            }
        }() ? Reflect.construct : function (t, e, i) {
            var n = [null];
            n.push.apply(n, e);
            n = new (Function.bind.apply(t, n));
            return i && a(n, i.prototype), n
        }).apply(null, arguments)
    }

    function d(t, e, i) {
        return (d = "undefined" != typeof Reflect && Reflect.get ? Reflect.get : function (t, e, i) {
            t = function (t, e) {
                for (; !Object.prototype.hasOwnProperty.call(t, e) && null !== (t = s(t));) ;
                return t
            }(t, e);
            if (t) {
                e = Object.getOwnPropertyDescriptor(t, e);
                return e.get ? e.get.call(i) : e.value
            }
        })(t, e, i || t)
    }

    function u(e) {
        return Object.keys(e).map(function (t) {
            return e[t]
        })
    }

    function h(t) {
        return Array.prototype.slice.call(t)
    }

    function p(t) {
        console.error("".concat(M, " ").concat(t))
    }

    function f(t) {
        return t && Promise.resolve(t) === t
    }

    function m(t) {
        return t instanceof Element || "object" === c(t = t) && t.jquery
    }

    function e(t) {
        var e, i = {};
        for (e in t) i[t[e]] = "swal2-" + t[e];
        return i
    }

    function g(t) {
        var e = F();
        return e ? e.querySelector(t) : null
    }

    function i(t) {
        return g(".".concat(t))
    }

    function v() {
        return h(q().querySelectorAll(".".concat(R.icon)))
    }

    function b() {
        var t = v().filter(function (t) {
            return dt(t)
        });
        return t.length ? t[0] : null
    }

    function y() {
        return i(R.title)
    }

    function w() {
        return i(R.content)
    }

    function x() {
        return i(R.image)
    }

    function _() {
        return i(R["progress-steps"])
    }

    function C() {
        return i(R["validation-message"])
    }

    function S() {
        return g(".".concat(R.actions, " .").concat(R.confirm))
    }

    function T() {
        return g(".".concat(R.actions, " .").concat(R.cancel))
    }

    function $() {
        return i(R.actions)
    }

    function E() {
        return i(R.header)
    }

    function k() {
        return i(R.footer)
    }

    function I() {
        return i(R["timer-progress-bar"])
    }

    function O() {
        return i(R.close)
    }

    function z() {
        var t = h(q().querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])')).sort(function (t, e) {
                return t = parseInt(t.getAttribute("tabindex")), (e = parseInt(e.getAttribute("tabindex"))) < t ? 1 : t < e ? -1 : 0
            }),
            e = h(q().querySelectorAll('\n  a[href],\n  area[href],\n  input:not([disabled]),\n  select:not([disabled]),\n  textarea:not([disabled]),\n  button:not([disabled]),\n  iframe,\n  object,\n  embed,\n  [tabindex="0"],\n  [contenteditable],\n  audio[controls],\n  video[controls],\n  summary\n')).filter(function (t) {
                return "-1" !== t.getAttribute("tabindex")
            });
        return function (t) {
            for (var e = [], i = 0; i < t.length; i++) -1 === e.indexOf(t[i]) && e.push(t[i]);
            return e
        }(t.concat(e)).filter(function (t) {
            return dt(t)
        })
    }

    function A() {
        return !V() && !document.body.classList.contains(R["no-backdrop"])
    }

    function P(e, t) {
        e.textContent = "", t && (h((t = (new DOMParser).parseFromString(t, "text/html")).querySelector("head").childNodes).forEach(function (t) {
            e.appendChild(t)
        }), h(t.querySelector("body").childNodes).forEach(function (t) {
            e.appendChild(t)
        }))
    }

    function D(t, e) {
        if (e) {
            for (var i = e.split(/\s+/), n = 0; n < i.length; n++) if (!t.classList.contains(i[n])) return;
            return 1
        }
    }

    function L(t, e, i) {
        var n, o = e;
        if (h((n = t).classList).forEach(function (t) {
            -1 === u(R).indexOf(t) && -1 === u(W).indexOf(t) && -1 === u(o.showClass).indexOf(t) && n.classList.remove(t)
        }), e.customClass && e.customClass[i]) {
            if ("string" != typeof e.customClass[i] && !e.customClass[i].forEach) return N("Invalid type of customClass.".concat(i, '! Expected string or iterable object, got "').concat(c(e.customClass[i]), '"'));
            rt(t, e.customClass[i])
        }
    }

    var M = "SweetAlert2:", N = function (t) {
            console.warn("".concat(M, " ").concat(t))
        }, H = [], j = function (t) {
            return "function" == typeof t ? t() : t
        }, B = Object.freeze({cancel: "cancel", backdrop: "backdrop", close: "close", esc: "esc", timer: "timer"}),
        R = e(["container", "shown", "height-auto", "iosfix", "popup", "modal", "no-backdrop", "no-transition", "toast", "toast-shown", "toast-column", "show", "hide", "close", "title", "header", "content", "html-container", "actions", "confirm", "cancel", "footer", "icon", "icon-content", "image", "input", "file", "range", "select", "radio", "checkbox", "label", "textarea", "inputerror", "validation-message", "progress-steps", "active-progress-step", "progress-step", "progress-step-line", "loading", "styled", "top", "top-start", "top-end", "top-left", "top-right", "center", "center-start", "center-end", "center-left", "center-right", "bottom", "bottom-start", "bottom-end", "bottom-left", "bottom-right", "grow-row", "grow-column", "grow-fullscreen", "rtl", "timer-progress-bar", "timer-progress-bar-container", "scrollbar-measure", "icon-success", "icon-warning", "icon-info", "icon-question", "icon-error"]),
        W = e(["success", "warning", "info", "question", "error"]), F = function () {
            return document.body.querySelector(".".concat(R.container))
        }, q = function () {
            return i(R.popup)
        }, V = function () {
            return document.body.classList.contains(R["toast-shown"])
        }, U = {previousBodyPadding: null};

    function G(t, e) {
        if (!e) return null;
        switch (e) {
            case"select":
            case"textarea":
            case"file":
                return ct(t, R[e]);
            case"checkbox":
                return t.querySelector(".".concat(R.checkbox, " input"));
            case"radio":
                return t.querySelector(".".concat(R.radio, " input:checked")) || t.querySelector(".".concat(R.radio, " input:first-child"));
            case"range":
                return t.querySelector(".".concat(R.range, " input"));
            default:
                return ct(t, R.input)
        }
    }

    function Y(t) {
        var e;
        t.focus(), "file" !== t.type && (e = t.value, t.value = "", t.value = e)
    }

    function X(t, e, i) {
        t && e && (e = "string" == typeof e ? e.split(/\s+/).filter(Boolean) : e).forEach(function (e) {
            t.forEach ? t.forEach(function (t) {
                i ? t.classList.add(e) : t.classList.remove(e)
            }) : i ? t.classList.add(e) : t.classList.remove(e)
        })
    }

    function K(t, e, i) {
        i || 0 === parseInt(i) ? t.style[e] = "number" == typeof i ? "".concat(i, "px") : i : t.style.removeProperty(e)
    }

    function Z(t, e) {
        e = 1 < arguments.length && void 0 !== e ? e : "flex";
        t.style.opacity = "", t.style.display = e
    }

    function Q(t) {
        t.style.opacity = "", t.style.display = "none"
    }

    function J(t, e, i) {
        e ? Z(t, i) : Q(t)
    }

    function tt(t) {
        var e = window.getComputedStyle(t), t = parseFloat(e.getPropertyValue("animation-duration") || "0"),
            e = parseFloat(e.getPropertyValue("transition-duration") || "0");
        return 0 < t || 0 < e
    }

    function et(t, e) {
        var e = 1 < arguments.length && void 0 !== e && e, i = I();
        dt(i) && (e && (i.style.transition = "none", i.style.width = "100%"), setTimeout(function () {
            i.style.transition = "width ".concat(t / 1e3, "s linear"), i.style.width = "0%"
        }, 10))
    }

    function it() {
        return "undefined" == typeof window || "undefined" == typeof document
    }

    function nt(t) {
        Ie.isVisible() && at !== t.target.value && Ie.resetValidationMessage(), at = t.target.value
    }

    function ot(t, e) {
        t instanceof HTMLElement ? e.appendChild(t) : "object" === c(t) ? ht(t, e) : t && P(e, t)
    }

    function st(t, e) {
        var i = $(), n = S(), o = T();
        e.showConfirmButton || e.showCancelButton || Q(i), L(i, e, "actions"), ft(n, "confirm", e), ft(o, "cancel", e), e.buttonsStyling ? function (t, e, i) {
            rt([t, e], R.styled), i.confirmButtonColor && (t.style.backgroundColor = i.confirmButtonColor), i.cancelButtonColor && (e.style.backgroundColor = i.cancelButtonColor);
            i = window.getComputedStyle(t).getPropertyValue("background-color");
            t.style.borderLeftColor = i, t.style.borderRightColor = i
        }(n, o, e) : (lt([n, o], R.styled), n.style.backgroundColor = n.style.borderLeftColor = n.style.borderRightColor = "", o.style.backgroundColor = o.style.borderLeftColor = o.style.borderRightColor = ""), e.reverseButtons && n.parentNode.insertBefore(o, n)
    }

    var at, rt = function (t, e) {
            X(t, e, !0)
        }, lt = function (t, e) {
            X(t, e, !1)
        }, ct = function (t, e) {
            for (var i = 0; i < t.childNodes.length; i++) if (D(t.childNodes[i], e)) return t.childNodes[i]
        }, dt = function (t) {
            return !(!t || !(t.offsetWidth || t.offsetHeight || t.getClientRects().length))
        },
        ut = '\n <div aria-labelledby="'.concat(R.title, '" aria-describedby="').concat(R.content, '" class="').concat(R.popup, '" tabindex="-1">\n   <div class="').concat(R.header, '">\n     <ul class="').concat(R["progress-steps"], '"></ul>\n     <div class="').concat(R.icon, " ").concat(W.error, '"></div>\n     <div class="').concat(R.icon, " ").concat(W.question, '"></div>\n     <div class="').concat(R.icon, " ").concat(W.warning, '"></div>\n     <div class="').concat(R.icon, " ").concat(W.info, '"></div>\n     <div class="').concat(R.icon, " ").concat(W.success, '"></div>\n     <img class="').concat(R.image, '" />\n     <h2 class="').concat(R.title, '" id="').concat(R.title, '"></h2>\n     <button type="button" class="').concat(R.close, '"></button>\n   </div>\n   <div class="').concat(R.content, '">\n     <div id="').concat(R.content, '" class="').concat(R["html-container"], '"></div>\n     <input class="').concat(R.input, '" />\n     <input type="file" class="').concat(R.file, '" />\n     <div class="').concat(R.range, '">\n       <input type="range" />\n       <output></output>\n     </div>\n     <select class="').concat(R.select, '"></select>\n     <div class="').concat(R.radio, '"></div>\n     <label for="').concat(R.checkbox, '" class="').concat(R.checkbox, '">\n       <input type="checkbox" />\n       <span class="').concat(R.label, '"></span>\n     </label>\n     <textarea class="').concat(R.textarea, '"></textarea>\n     <div class="').concat(R["validation-message"], '" id="').concat(R["validation-message"], '"></div>\n   </div>\n   <div class="').concat(R.actions, '">\n     <button type="button" class="').concat(R.confirm, '">OK</button>\n     <button type="button" class="').concat(R.cancel, '">Cancel</button>\n   </div>\n   <div class="').concat(R.footer, '"></div>\n   <div class="').concat(R["timer-progress-bar-container"], '">\n     <div class="').concat(R["timer-progress-bar"], '"></div>\n   </div>\n </div>\n').replace(/(^|\n)\s*/g, ""),
        ht = function (t, e) {
            t.jquery ? function (t, e) {
                if (t.textContent = "", 0 in e) for (var i = 0; i in e; i++) t.appendChild(e[i].cloneNode(!0)); else t.appendChild(e.cloneNode(!0))
            }(e, t) : P(e, t.toString())
        }, pt = function () {
            if (it()) return !1;
            var t, e = document.createElement("div"), i = {
                WebkitAnimation: "webkitAnimationEnd",
                OAnimation: "oAnimationEnd oanimationend",
                animation: "animationend"
            };
            for (t in i) if (Object.prototype.hasOwnProperty.call(i, t) && void 0 !== e.style[t]) return i[t];
            return !1
        }();

    function ft(t, e, i) {
        var n;
        J(t, i["show".concat((n = e).charAt(0).toUpperCase() + n.slice(1), "Button")], "inline-block"), P(t, i["".concat(e, "ButtonText")]), t.setAttribute("aria-label", i["".concat(e, "ButtonAriaLabel")]), t.className = R[e], L(t, i, "".concat(e, "Button")), rt(t, i["".concat(e, "ButtonClass")])
    }

    function mt(t, e) {
        t.placeholder && !e.inputPlaceholder || (t.placeholder = e.inputPlaceholder)
    }

    function gt(t) {
        return t = R[t] || R.input, ct(w(), t)
    }

    var vt = {promise: new WeakMap, innerParams: new WeakMap, domCache: new WeakMap},
        bt = ["input", "file", "range", "select", "radio", "checkbox", "textarea"], yt = function (t) {
            if (!_t[t.input]) return p('Unexpected type of input! Expected "text", "email", "password", "number", "tel", "select", "radio", "checkbox", "textarea", "file" or "url", got "'.concat(t.input, '"'));
            var e = gt(t.input), i = _t[t.input](e, t);
            Z(i), setTimeout(function () {
                Y(i)
            })
        }, wt = function (t, e) {
            var i = G(w(), t);
            if (i) for (var n in function (t) {
                for (var e = 0; e < t.attributes.length; e++) {
                    var i = t.attributes[e].name;
                    -1 === ["type", "value", "style"].indexOf(i) && t.removeAttribute(i)
                }
            }(i), e) "range" === t && "placeholder" === n || i.setAttribute(n, e[n])
        }, xt = function (t) {
            var e = gt(t.input);
            t.customClass && rt(e, t.customClass.input)
        }, _t = {};

    function Ct() {
        return F().getAttribute("data-queue-step")
    }

    function St(t, e) {
        var i;
        L(E(), e, "header"), function (o) {
            var s = _();
            if (!o.progressSteps || 0 === o.progressSteps.length) return Q(s);
            Z(s), s.textContent = "";
            var a = parseInt(void 0 === o.currentProgressStep ? Ct() : o.currentProgressStep);
            a >= o.progressSteps.length && N("Invalid currentProgressStep parameter, it should be less than progressSteps.length (currentProgressStep like JS arrays starts from 0)"), o.progressSteps.forEach(function (t, e) {
                var i, n = (i = t, n = document.createElement("li"), rt(n, R["progress-step"]), P(n, i), n);
                s.appendChild(n), e === a && rt(n, R["active-progress-step"]), e !== o.progressSteps.length - 1 && (e = t, t = document.createElement("li"), rt(t, R["progress-step-line"]), e.progressStepsDistance && (t.style.width = e.progressStepsDistance), t = t, s.appendChild(t))
            })
        }(e), i = e, (t = vt.innerParams.get(t)) && i.icon === t.icon && b() ? L(b(), i, "icon") : (kt(), i.icon && (-1 !== Object.keys(W).indexOf(i.icon) ? (Z(t = g(".".concat(R.icon, ".").concat(W[i.icon]))), Ot(t, i), It(), L(t, i, "icon"), rt(t, i.showClass.icon)) : p('Unknown icon! Expected "success", "error", "warning", "info" or "question", got "'.concat(i.icon, '"')))), function (t) {
            var e = x();
            if (!t.imageUrl) return Q(e);
            Z(e), e.setAttribute("src", t.imageUrl), e.setAttribute("alt", t.imageAlt), K(e, "width", t.imageWidth), K(e, "height", t.imageHeight), e.className = R.image, L(e, t, "image")
        }(e), t = e, J(i = y(), t.title || t.titleText), t.title && ot(t.title, i), t.titleText && (i.innerText = t.titleText), L(i, t, "title"), t = e, P(e = O(), t.closeButtonHtml), L(e, t, "closeButton"), J(e, t.showCloseButton), e.setAttribute("aria-label", t.closeButtonAriaLabel)
    }

    function Tt(t, e) {
        var n, o, s, i, a, r = e, l = q();
        K(l, "width", r.width), K(l, "padding", r.padding), r.background && (l.style.background = r.background), a = r, (i = l).className = "".concat(R.popup, " ").concat(dt(i) ? a.showClass.popup : ""), a.toast ? (rt([document.documentElement, document.body], R["toast-shown"]), rt(i, R.toast)) : rt(i, R.modal), L(i, a, "popup"), "string" == typeof a.customClass && rt(i, a.customClass), a.icon && rt(i, R["icon-".concat(a.icon)]), r = e, (l = F()) && ("string" == typeof (i = r.backdrop) ? l.style.background = i : i || rt([document.documentElement, document.body], R["no-backdrop"]), !r.backdrop && r.allowOutsideClick && N('"allowOutsideClick" parameter requires `backdrop` parameter to be set to `true`'), a = l, (i = r.position) in R ? rt(a, R[i]) : (N('The "position" parameter is not valid, defaulting to "center"'), rt(a, R.center)), i = l, !(a = r.grow) || "string" != typeof a || (a = "grow-".concat(a)) in R && rt(i, R[a]), L(l, r, "container"), (r = document.body.getAttribute("data-swal2-queue-step")) && (l.setAttribute("data-queue-step", r), document.body.removeAttribute("data-swal2-queue-step"))), St(t, e), l = t, r = e, t = w().querySelector("#".concat(R.content)), r.html ? (ot(r.html, t), Z(t, "block")) : r.text ? (t.textContent = r.text, Z(t, "block")) : Q(t), l = l, n = r, o = w(), l = vt.innerParams.get(l), s = !l || n.input !== l.input, bt.forEach(function (t) {
            var e = R[t], i = ct(o, e);
            wt(t, n.inputAttributes), i.className = e, s && Q(i)
        }), n.input && (s && yt(n), xt(n)), L(w(), r, "content"), st(0, e), l = e, J(r = k(), l.footer), l.footer && ot(l.footer, r), L(r, l, "footer"), "function" == typeof e.onRender && e.onRender(q())
    }

    function $t() {
        return S() && S().click()
    }

    _t.text = _t.email = _t.password = _t.number = _t.tel = _t.url = function (t, e) {
        return "string" == typeof e.inputValue || "number" == typeof e.inputValue ? t.value = e.inputValue : f(e.inputValue) || N('Unexpected type of inputValue! Expected "string", "number" or "Promise", got "'.concat(c(e.inputValue), '"')), mt(t, e), t.type = e.input, t
    }, _t.file = function (t, e) {
        return mt(t, e), t
    }, _t.range = function (t, e) {
        var i = t.querySelector("input"), n = t.querySelector("output");
        return i.value = e.inputValue, i.type = e.input, n.value = e.inputValue, t
    }, _t.select = function (t, e) {
        var i;
        return t.textContent = "", e.inputPlaceholder && (P(i = document.createElement("option"), e.inputPlaceholder), i.value = "", i.disabled = !0, i.selected = !0, t.appendChild(i)), t
    }, _t.radio = function (t) {
        return t.textContent = "", t
    }, _t.checkbox = function (t, e) {
        var i = G(w(), "checkbox");
        return i.value = 1, i.id = R.checkbox, i.checked = Boolean(e.inputValue), P(t.querySelector("span"), e.inputPlaceholder), t
    }, _t.textarea = function (e, t) {
        var i, n;
        return e.value = t.inputValue, mt(e, t), "MutationObserver" in window && (i = parseInt(window.getComputedStyle(q()).width), n = parseInt(window.getComputedStyle(q()).paddingLeft) + parseInt(window.getComputedStyle(q()).paddingRight), new MutationObserver(function () {
            var t = e.offsetWidth + n;
            q().style.width = i < t ? "".concat(t, "px") : null
        }).observe(e, {attributes: !0, attributeFilter: ["style"]})), e
    };

    function Et(t) {
        return '<div class="'.concat(R["icon-content"], '">').concat(t, "</div>")
    }

    var kt = function () {
        for (var t = v(), e = 0; e < t.length; e++) Q(t[e])
    }, It = function () {
        for (var t = q(), e = window.getComputedStyle(t).getPropertyValue("background-color"), i = t.querySelectorAll("[class^=swal2-success-circular-line], .swal2-success-fix"), n = 0; n < i.length; n++) i[n].style.backgroundColor = e
    }, Ot = function (t, e) {
        t.textContent = "", e.iconHtml ? P(t, Et(e.iconHtml)) : "success" === e.icon ? P(t, '\n      <div class="swal2-success-circular-line-left"></div>\n      <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>\n      <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>\n      <div class="swal2-success-circular-line-right"></div>\n    ') : "error" === e.icon ? P(t, '\n      <span class="swal2-x-mark">\n        <span class="swal2-x-mark-line-left"></span>\n        <span class="swal2-x-mark-line-right"></span>\n      </span>\n    ') : P(t, Et({
            question: "?",
            warning: "!",
            info: "i"
        }[e.icon]))
    }, zt = [];

    function At() {
        (t = q()) || Ie.fire();
        var t = q(), e = $(), i = S();
        Z(e), Z(i, "inline-block"), rt([t, e], R.loading), i.disabled = !0, t.setAttribute("data-loading", !0), t.setAttribute("aria-busy", !0), t.focus()
    }

    function Pt() {
        if (Nt.timeout) return function () {
            var t = I(), e = parseInt(window.getComputedStyle(t).width);
            t.style.removeProperty("transition"), t.style.width = "100%";
            var i = parseInt(window.getComputedStyle(t).width), i = parseInt(e / i * 100);
            t.style.removeProperty("transition"), t.style.width = "".concat(i, "%")
        }(), Nt.timeout.stop()
    }

    function Dt() {
        if (Nt.timeout) {
            var t = Nt.timeout.start();
            return et(t), t
        }
    }

    function Lt(t) {
        return Object.prototype.hasOwnProperty.call(Ht, t)
    }

    function Mt(t) {
        return Bt[t]
    }

    var Nt = {}, Ht = {
            title: "",
            titleText: "",
            text: "",
            html: "",
            footer: "",
            icon: void 0,
            iconHtml: void 0,
            toast: !1,
            animation: !0,
            showClass: {popup: "swal2-show", backdrop: "swal2-backdrop-show", icon: "swal2-icon-show"},
            hideClass: {popup: "swal2-hide", backdrop: "swal2-backdrop-hide", icon: "swal2-icon-hide"},
            customClass: void 0,
            target: "body",
            backdrop: !0,
            heightAuto: !0,
            allowOutsideClick: !0,
            allowEscapeKey: !0,
            allowEnterKey: !0,
            stopKeydownPropagation: !0,
            keydownListenerCapture: !1,
            showConfirmButton: !0,
            showCancelButton: !1,
            preConfirm: void 0,
            confirmButtonText: "OK",
            confirmButtonAriaLabel: "",
            confirmButtonColor: void 0,
            cancelButtonText: "Cancel",
            cancelButtonAriaLabel: "",
            cancelButtonColor: void 0,
            buttonsStyling: !0,
            reverseButtons: !1,
            focusConfirm: !0,
            focusCancel: !1,
            showCloseButton: !1,
            closeButtonHtml: "&times;",
            closeButtonAriaLabel: "Close this dialog",
            showLoaderOnConfirm: !1,
            imageUrl: void 0,
            imageWidth: void 0,
            imageHeight: void 0,
            imageAlt: "",
            timer: void 0,
            timerProgressBar: !1,
            width: void 0,
            padding: void 0,
            background: void 0,
            input: void 0,
            inputPlaceholder: "",
            inputValue: "",
            inputOptions: {},
            inputAutoTrim: !0,
            inputAttributes: {},
            inputValidator: void 0,
            validationMessage: void 0,
            grow: !1,
            position: "center",
            progressSteps: [],
            currentProgressStep: void 0,
            progressStepsDistance: void 0,
            onBeforeOpen: void 0,
            onOpen: void 0,
            onRender: void 0,
            onClose: void 0,
            onAfterClose: void 0,
            onDestroy: void 0,
            scrollbarPadding: !0
        },
        jt = ["title", "titleText", "text", "html", "icon", "hideClass", "customClass", "allowOutsideClick", "allowEscapeKey", "showConfirmButton", "showCancelButton", "confirmButtonText", "confirmButtonAriaLabel", "confirmButtonColor", "cancelButtonText", "cancelButtonAriaLabel", "cancelButtonColor", "buttonsStyling", "reverseButtons", "imageUrl", "imageWidth", "imageHeight", "imageAlt", "progressSteps", "currentProgressStep"],
        Bt = {animation: 'showClass" and "hideClass'},
        Rt = ["allowOutsideClick", "allowEnterKey", "backdrop", "focusConfirm", "focusCancel", "heightAuto", "keydownListenerCapture"],
        Wt = Object.freeze({
            isValidParameter: Lt,
            isUpdatableParameter: function (t) {
                return -1 !== jt.indexOf(t)
            },
            isDeprecatedParameter: Mt,
            argsToParams: function (i) {
                var n = {};
                return "object" !== c(i[0]) || m(i[0]) ? ["title", "html", "icon"].forEach(function (t, e) {
                    e = i[e];
                    "string" == typeof e || m(e) ? n[t] = e : void 0 !== e && p("Unexpected type of ".concat(t, '! Expected "string" or "Element", got ').concat(c(e)))
                }) : l(n, i[0]), n
            },
            isVisible: function () {
                return dt(q())
            },
            clickConfirm: $t,
            clickCancel: function () {
                return T() && T().click()
            },
            getContainer: F,
            getPopup: q,
            getTitle: y,
            getContent: w,
            getHtmlContainer: function () {
                return i(R["html-container"])
            },
            getImage: x,
            getIcon: b,
            getIcons: v,
            getCloseButton: O,
            getActions: $,
            getConfirmButton: S,
            getCancelButton: T,
            getHeader: E,
            getFooter: k,
            getTimerProgressBar: I,
            getFocusableElements: z,
            getValidationMessage: C,
            isLoading: function () {
                return q().hasAttribute("data-loading")
            },
            fire: function () {
                for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
                return r(this, e)
            },
            mixin: function (e) {
                return function (t, e) {
                    if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
                    t.prototype = Object.create(e && e.prototype, {
                        constructor: {
                            value: t,
                            writable: !0,
                            configurable: !0
                        }
                    }), e && a(t, e)
                }(i, this), t(i, [{
                    key: "_main", value: function (t) {
                        return d(s(i.prototype), "_main", this).call(this, l({}, e, t))
                    }
                }]), i;

                function i() {
                    return n(this, i), t = this, !(e = s(i).apply(this, arguments)) || "object" != typeof e && "function" != typeof e ? function (t) {
                        if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                        return t
                    }(t) : e;
                    var t, e
                }
            },
            queue: function (t) {
                var s = this;

                function a(t, e) {
                    zt = [], t(e)
                }

                zt = t;
                var r = [];
                return new Promise(function (o) {
                    !function e(i, n) {
                        i < zt.length ? (document.body.setAttribute("data-swal2-queue-step", i), s.fire(zt[i]).then(function (t) {
                            void 0 !== t.value ? (r.push(t.value), e(i + 1, n)) : a(o, {dismiss: t.dismiss})
                        })) : a(o, {value: r})
                    }(0)
                })
            },
            getQueueStep: Ct,
            insertQueueStep: function (t, e) {
                return e && e < zt.length ? zt.splice(e, 0, t) : zt.push(t)
            },
            deleteQueueStep: function (t) {
                void 0 !== zt[t] && zt.splice(t, 1)
            },
            showLoading: At,
            enableLoading: At,
            getTimerLeft: function () {
                return Nt.timeout && Nt.timeout.getTimerLeft()
            },
            stopTimer: Pt,
            resumeTimer: Dt,
            toggleTimer: function () {
                var t = Nt.timeout;
                return t && (t.running ? Pt : Dt)()
            },
            increaseTimer: function (t) {
                if (Nt.timeout) {
                    t = Nt.timeout.increase(t);
                    return et(t, !0), t
                }
            },
            isTimerRunning: function () {
                return Nt.timeout && Nt.timeout.isRunning()
            }
        });

    function Ft() {
        var t, e = vt.innerParams.get(this);
        e && (t = vt.domCache.get(this), e.showConfirmButton || (Q(t.confirmButton), e.showCancelButton || Q(t.actions)), lt([t.popup, t.actions], R.loading), t.popup.removeAttribute("aria-busy"), t.popup.removeAttribute("data-loading"), t.confirmButton.disabled = !1, t.cancelButton.disabled = !1)
    }

    function qt() {
        return window.MSInputMethodContext && document.documentMode
    }

    function Vt() {
        var t = F(), e = q();
        t.style.removeProperty("align-items"), e.offsetTop < 0 && (t.style.alignItems = "flex-start")
    }

    var Ut = function () {
        var e, i = F();
        i.ontouchstart = function (t) {
            e = t.target === i || !(i.scrollHeight > i.clientHeight) && "INPUT" !== t.target.tagName
        }, i.ontouchmove = function (t) {
            e && (t.preventDefault(), t.stopPropagation())
        }
    }, Gt = {swalPromiseResolve: new WeakMap};

    function Yt(t, e, i, n) {
        i ? Kt(t, n) : (new Promise(function (t) {
            var e = window.scrollX, i = window.scrollY;
            Nt.restoreFocusTimeout = setTimeout(function () {
                Nt.previousActiveElement && Nt.previousActiveElement.focus ? (Nt.previousActiveElement.focus(), Nt.previousActiveElement = null) : document.body && document.body.focus(), t()
            }, 100), void 0 !== e && void 0 !== i && window.scrollTo(e, i)
        }).then(function () {
            return Kt(t, n)
        }), Nt.keydownTarget.removeEventListener("keydown", Nt.keydownHandler, {capture: Nt.keydownListenerCapture}), Nt.keydownHandlerAdded = !1), e.parentNode && !document.body.getAttribute("data-swal2-queue-step") && e.parentNode.removeChild(e), A() && (null !== U.previousBodyPadding && (document.body.style.paddingRight = "".concat(U.previousBodyPadding, "px"), U.previousBodyPadding = null), D(document.body, R.iosfix) && (e = parseInt(document.body.style.top, 10), lt(document.body, R.iosfix), document.body.style.top = "", document.body.scrollTop = -1 * e), "undefined" != typeof window && qt() && window.removeEventListener("resize", Vt), h(document.body.children).forEach(function (t) {
            t.hasAttribute("data-previous-aria-hidden") ? (t.setAttribute("aria-hidden", t.getAttribute("data-previous-aria-hidden")), t.removeAttribute("data-previous-aria-hidden")) : t.removeAttribute("aria-hidden")
        })), lt([document.documentElement, document.body], [R.shown, R["height-auto"], R["no-backdrop"], R["toast-shown"], R["toast-column"]])
    }

    function Xt(t) {
        var e, i, n, o, s, a, r, l = q();
        !l || (r = vt.innerParams.get(this)) && !D(l, r.hideClass.popup) && (e = Gt.swalPromiseResolve.get(this), lt(l, r.showClass.popup), rt(l, r.hideClass.popup), a = F(), lt(a, r.showClass.backdrop), rt(a, r.hideClass.backdrop), i = this, n = l, o = r, a = F(), l = pt && tt(n), r = o.onClose, o = o.onAfterClose, null !== r && "function" == typeof r && r(n), l ? (s = n, n = o, Nt.swalCloseEventFinishedCallback = Yt.bind(null, i, a, V(), n), s.addEventListener(pt, function (t) {
            t.target === s && (Nt.swalCloseEventFinishedCallback(), delete Nt.swalCloseEventFinishedCallback)
        })) : Yt(i, a, V(), o), e(t || {}))
    }

    var Kt = function (t, e) {
        setTimeout(function () {
            "function" == typeof e && e(), t._destroy()
        })
    };

    function Zt(t, e, i) {
        var n = vt.domCache.get(t);
        e.forEach(function (t) {
            n[t].disabled = i
        })
    }

    function Qt(t, e) {
        if (!t) return !1;
        if ("radio" === t.type) for (var i = t.parentNode.parentNode.querySelectorAll("input"), n = 0; n < i.length; n++) i[n].disabled = e; else t.disabled = e
    }

    var Jt = (t(ee, [{
        key: "start", value: function () {
            return this.running || (this.running = !0, this.started = new Date, this.id = setTimeout(this.callback, this.remaining)), this.remaining
        }
    }, {
        key: "stop", value: function () {
            return this.running && (this.running = !1, clearTimeout(this.id), this.remaining -= new Date - this.started), this.remaining
        }
    }, {
        key: "increase", value: function (t) {
            var e = this.running;
            return e && this.stop(), this.remaining += t, e && this.start(), this.remaining
        }
    }, {
        key: "getTimerLeft", value: function () {
            return this.running && (this.stop(), this.start()), this.remaining
        }
    }, {
        key: "isRunning", value: function () {
            return this.running
        }
    }]), ee), te = {
        email: function (t, e) {
            return /^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,24}$/.test(t) ? Promise.resolve() : Promise.resolve(e || "Invalid email address")
        }, url: function (t, e) {
            return /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,63}\b([-a-zA-Z0-9@:%_+.~#?&/=]*)$/.test(t) ? Promise.resolve() : Promise.resolve(e || "Invalid URL")
        }
    };

    function ee(t, e) {
        n(this, ee), this.callback = t, this.remaining = e, this.running = !1, this.start()
    }

    function ie(t) {
        var e, i, n, o, s, a, r;
        (e = t).inputValidator || Object.keys(te).forEach(function (t) {
            e.input === t && (e.inputValidator = te[t])
        }), t.showLoaderOnConfirm && !t.preConfirm && N("showLoaderOnConfirm is set to true, but preConfirm is not defined.\nshowLoaderOnConfirm should be used together with preConfirm, see usage example:\nhttps://sweetalert2.github.io/#ajax-request"), t.animation = j(t.animation), (s = t).target && ("string" != typeof s.target || document.querySelector(s.target)) && ("string" == typeof s.target || s.target.appendChild) || (N('Target parameter is not valid, defaulting to "body"'), s.target = "body"), "string" == typeof t.title && (t.title = t.title.split("\n").join("<br />")), i = t, r = !!(a = F()) && (a.parentNode.removeChild(a), lt([document.documentElement, document.body], [R["no-backdrop"], R["toast-shown"], R["has-column"]]), !0), it() ? p("SweetAlert2 requires document to initialize") : ((s = document.createElement("div")).className = R.container, r && rt(s, R["no-transition"]), P(s, ut), (a = "string" == typeof (t = i.target) ? document.querySelector(t) : t).appendChild(s), r = i, (t = q()).setAttribute("role", r.toast ? "alert" : "dialog"), t.setAttribute("aria-live", r.toast ? "polite" : "assertive"), r.toast || t.setAttribute("aria-modal", "true"), "rtl" === window.getComputedStyle(a).direction && rt(F(), R.rtl), s = w(), i = ct(s, R.input), r = ct(s, R.file), n = s.querySelector(".".concat(R.range, " input")), o = s.querySelector(".".concat(R.range, " output")), t = ct(s, R.select), a = s.querySelector(".".concat(R.checkbox, " input")), s = ct(s, R.textarea), i.oninput = nt, r.onchange = nt, t.onchange = nt, a.onchange = nt, s.oninput = nt, n.oninput = function (t) {
            nt(t), o.value = n.value
        }, n.onchange = function (t) {
            nt(t), n.nextSibling.value = n.value
        })
    }

    function ne(t) {
        var e, i, n, o = F(), s = q();
        "function" == typeof t.onBeforeOpen && t.onBeforeOpen(s), i = s, rt(o, (n = t).showClass.backdrop), Z(i), rt(i, n.showClass.popup), rt([document.documentElement, document.body], R.shown), n.heightAuto && n.backdrop && !n.toast && rt([document.documentElement, document.body], R["height-auto"]), i = o, n = s, pt && tt(n) ? (i.style.overflowY = "hidden", n.addEventListener(pt, oe)) : i.style.overflowY = "auto", A() && (e = o, i = t.scrollbarPadding, function () {
            var t;
            (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream || "MacIntel" === navigator.platform && 1 < navigator.maxTouchPoints) && !D(document.body, R.iosfix) && (t = document.body.scrollTop, document.body.style.top = "".concat(-1 * t, "px"), rt(document.body, R.iosfix), Ut())
        }(), "undefined" != typeof window && qt() && (Vt(), window.addEventListener("resize", Vt)), h(document.body.children).forEach(function (t) {
            var e, i;
            t === F() || (e = t, i = F(), "function" == typeof e.contains && e.contains(i)) || (t.hasAttribute("aria-hidden") && t.setAttribute("data-previous-aria-hidden", t.getAttribute("aria-hidden")), t.setAttribute("aria-hidden", "true"))
        }), !i || null === U.previousBodyPadding && document.body.scrollHeight > window.innerHeight && (U.previousBodyPadding = parseInt(window.getComputedStyle(document.body).getPropertyValue("padding-right")), document.body.style.paddingRight = "".concat(U.previousBodyPadding + function () {
            var t = document.createElement("div");
            t.className = R["scrollbar-measure"], document.body.appendChild(t);
            var e = t.getBoundingClientRect().width - t.clientWidth;
            return document.body.removeChild(t), e
        }(), "px")), setTimeout(function () {
            e.scrollTop = 0
        })), V() || Nt.previousActiveElement || (Nt.previousActiveElement = document.activeElement), "function" == typeof t.onOpen && setTimeout(function () {
            return t.onOpen(s)
        }), lt(o, R["no-transition"])
    }

    function oe(t) {
        var e = q();
        t.target === e && (t = F(), e.removeEventListener(pt, oe), t.style.overflowY = "auto")
    }

    function se(t, e) {
        var i, n, o, s, a, r;

        function l(t) {
            return ve[a.input](r, be(t), a)
        }

        "select" === e.input || "radio" === e.input ? (s = t, a = e, r = w(), f(a.inputOptions) ? (At(), a.inputOptions.then(function (t) {
            s.hideLoading(), l(t)
        })) : "object" === c(a.inputOptions) ? l(a.inputOptions) : p("Unexpected type of inputOptions! Expected object, Map or Promise, got ".concat(c(a.inputOptions)))) : -1 !== ["text", "email", "number", "tel", "textarea"].indexOf(e.input) && f(e.inputValue) && (n = e, Q(o = (i = t).getInput()), n.inputValue.then(function (t) {
            o.value = "number" === n.input ? parseFloat(t) || 0 : "".concat(t), Z(o), o.focus(), i.hideLoading()
        }).catch(function (t) {
            p("Error in inputValue promise: ".concat(t)), o.value = "", Z(o), o.focus(), i.hideLoading()
        }))
    }

    function ae(t, e) {
        var i, n, o;
        t.disableButtons(), e.input ? (i = t, o = function (t) {
            var e = i.getInput();
            if (!e) return null;
            switch (t.input) {
                case"checkbox":
                    return fe(e);
                case"radio":
                    return me(e);
                case"file":
                    return ge(e);
                default:
                    return t.inputAutoTrim ? e.value.trim() : e.value
            }
        }(n = e), n.inputValidator ? (i.disableInput(), Promise.resolve().then(function () {
            return n.inputValidator(o, n.validationMessage)
        }).then(function (t) {
            i.enableButtons(), i.enableInput(), t ? i.showValidationMessage(t) : ye(i, n, o)
        })) : i.getInput().checkValidity() ? ye(i, n, o) : (i.enableButtons(), i.showValidationMessage(n.validationMessage))) : ye(t, e, !0)
    }

    function re(t, e) {
        t.closePopup({value: e})
    }

    function le(o, t, e, s) {
        t.keydownTarget && t.keydownHandlerAdded && (t.keydownTarget.removeEventListener("keydown", t.keydownHandler, {capture: t.keydownListenerCapture}), t.keydownHandlerAdded = !1), e.toast || (t.keydownHandler = function (t) {
            return e = o, i = t, n = s, (t = vt.innerParams.get(e)).stopKeydownPropagation && i.stopPropagation(), void ("Enter" === i.key ? _e(e, i, t) : "Tab" === i.key ? Ce(i, t) : -1 !== we.indexOf(i.key) ? Se() : -1 !== xe.indexOf(i.key) && Te(i, t, n));
            var e, i, n
        }, t.keydownTarget = e.keydownListenerCapture ? window : q(), t.keydownListenerCapture = e.keydownListenerCapture, t.keydownTarget.addEventListener("keydown", t.keydownHandler, {capture: t.keydownListenerCapture}), t.keydownHandlerAdded = !0)
    }

    function ce(t, e, i) {
        var n = z();
        if (0 < n.length) return (e += i) === n.length ? e = 0 : -1 === e && (e = n.length - 1), n[e].focus();
        q().focus()
    }

    function de(t, e, i) {
        var n, o, s, a, r, l, c;
        vt.innerParams.get(t).toast ? (l = t, c = i, e.popup.onclick = function () {
            var t = vt.innerParams.get(l);
            t.showConfirmButton || t.showCancelButton || t.showCloseButton || t.input || c(B.close)
        }) : ((r = e).popup.onmousedown = function () {
            r.container.onmouseup = function (t) {
                r.container.onmouseup = void 0, t.target === r.container && ($e = !0)
            }
        }, (a = e).container.onmousedown = function () {
            a.popup.onmouseup = function (t) {
                a.popup.onmouseup = void 0, t.target !== a.popup && !a.popup.contains(t.target) || ($e = !0)
            }
        }, n = t, s = i, (o = e).container.onclick = function (t) {
            var e = vt.innerParams.get(n);
            $e ? $e = !1 : t.target === o.container && j(e.allowOutsideClick) && s(B.backdrop)
        })
    }

    function ue(t, e) {
        return !e.toast && (j(e.allowEnterKey) ? e.focusCancel && dt(t.cancelButton) ? t.cancelButton.focus() : e.focusConfirm && dt(t.confirmButton) ? t.confirmButton.focus() : void ce(0, -1, 1) : void (document.activeElement && "function" == typeof document.activeElement.blur && document.activeElement.blur()))
    }

    function he(t) {
        for (var e in t) t[e] = new WeakMap
    }

    var pe, fe = function (t) {
            return t.checked ? 1 : 0
        }, me = function (t) {
            return t.checked ? t.value : null
        }, ge = function (t) {
            return t.files.length ? null !== t.getAttribute("multiple") ? t.files : t.files[0] : null
        }, ve = {
            select: function (t, e, n) {
                var o = ct(t, R.select);
                e.forEach(function (t) {
                    var e = t[0], i = t[1], t = document.createElement("option");
                    t.value = e, P(t, i), n.inputValue.toString() === e.toString() && (t.selected = !0), o.appendChild(t)
                }), o.focus()
            }, radio: function (t, e, o) {
                var s = ct(t, R.radio);
                e.forEach(function (t) {
                    var e = t[0], i = t[1], n = document.createElement("input"), t = document.createElement("label");
                    n.type = "radio", n.name = R.radio, n.value = e, o.inputValue.toString() === e.toString() && (n.checked = !0);
                    e = document.createElement("span");
                    P(e, i), e.className = R.label, t.appendChild(n), t.appendChild(e), s.appendChild(t)
                });
                e = s.querySelectorAll("input");
                e.length && e[0].focus()
            }
        }, be = function (e) {
            var i = [];
            return "undefined" != typeof Map && e instanceof Map ? e.forEach(function (t, e) {
                i.push([e, t])
            }) : Object.keys(e).forEach(function (t) {
                i.push([t, e[t]])
            }), i
        }, ye = function (e, t, i) {
            t.showLoaderOnConfirm && At(), t.preConfirm ? (e.resetValidationMessage(), Promise.resolve().then(function () {
                return t.preConfirm(i, t.validationMessage)
            }).then(function (t) {
                dt(C()) || !1 === t ? e.hideLoading() : re(e, void 0 === t ? i : t)
            })) : re(e, i)
        }, we = ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Left", "Right", "Up", "Down"], xe = ["Escape", "Esc"],
        _e = function (t, e, i) {
            !e.isComposing && e.target && t.getInput() && e.target.outerHTML === t.getInput().outerHTML && -1 === ["textarea", "file"].indexOf(i.input) && ($t(), e.preventDefault())
        }, Ce = function (t) {
            for (var e = t.target, i = z(), n = -1, o = 0; o < i.length; o++) if (e === i[o]) {
                n = o;
                break
            }
            t.shiftKey ? ce(0, n, -1) : ce(0, n, 1), t.stopPropagation(), t.preventDefault()
        }, Se = function () {
            var t = S(), e = T();
            document.activeElement === t && dt(e) ? e.focus() : document.activeElement === e && dt(t) && t.focus()
        }, Te = function (t, e, i) {
            j(e.allowEscapeKey) && (t.preventDefault(), i(B.esc))
        }, $e = !1, Ee = Object.freeze({
            hideLoading: Ft, disableLoading: Ft, getInput: function (t) {
                var e = vt.innerParams.get(t || this), t = vt.domCache.get(t || this);
                return t ? G(t.content, e.input) : null
            }, close: Xt, closePopup: Xt, closeModal: Xt, closeToast: Xt, enableButtons: function () {
                Zt(this, ["confirmButton", "cancelButton"], !1)
            }, disableButtons: function () {
                Zt(this, ["confirmButton", "cancelButton"], !0)
            }, enableInput: function () {
                return Qt(this.getInput(), !1)
            }, disableInput: function () {
                return Qt(this.getInput(), !0)
            }, showValidationMessage: function (t) {
                var e = vt.domCache.get(this);
                P(e.validationMessage, t);
                t = window.getComputedStyle(e.popup);
                e.validationMessage.style.marginLeft = "-".concat(t.getPropertyValue("padding-left")), e.validationMessage.style.marginRight = "-".concat(t.getPropertyValue("padding-right")), Z(e.validationMessage);
                e = this.getInput();
                e && (e.setAttribute("aria-invalid", !0), e.setAttribute("aria-describedBy", R["validation-message"]), Y(e), rt(e, R.inputerror))
            }, resetValidationMessage: function () {
                var t = vt.domCache.get(this);
                t.validationMessage && Q(t.validationMessage);
                t = this.getInput();
                t && (t.removeAttribute("aria-invalid"), t.removeAttribute("aria-describedBy"), lt(t, R.inputerror))
            }, getProgressSteps: function () {
                return vt.domCache.get(this).progressSteps
            }, _main: function (t) {
                !function (t) {
                    for (var e in t) Lt(n = e) || N('Unknown parameter "'.concat(n, '"')), t.toast && (i = e, -1 !== Rt.indexOf(i) && N('The parameter "'.concat(i, '" is incompatible with toasts'))), Mt(i = e) && (e = i, i = Bt[i], i = '"'.concat(e, '" is deprecated and will be removed in the next major release. Please use "').concat(i, '" instead.'), -1 === H.indexOf(i) && (H.push(i), N(i)));
                    var i, n
                }(t), Nt.currentInstance && Nt.currentInstance._destroy(), Nt.currentInstance = this;
                var e,
                    i = (e = l({}, Ht.showClass, (n = t).showClass), i = l({}, Ht.hideClass, n.hideClass), (t = l({}, Ht, n)).showClass = e, t.hideClass = i, !1 === n.animation && (t.showClass = {
                        popup: "swal2-noanimation",
                        backdrop: "swal2-noanimation"
                    }, t.hideClass = {}), t);
                ie(i), Object.freeze(i), Nt.timeout && (Nt.timeout.stop(), delete Nt.timeout), clearTimeout(Nt.restoreFocusTimeout);
                var n, s, a, r, t = (n = this, t = {
                    popup: q(),
                    container: F(),
                    content: w(),
                    actions: $(),
                    confirmButton: S(),
                    cancelButton: T(),
                    closeButton: O(),
                    validationMessage: C(),
                    progressSteps: _()
                }, vt.domCache.set(n, t), t);
                return Tt(this, i), vt.innerParams.set(this, i), s = this, a = t, r = i, new Promise(function (t) {
                    function e(t) {
                        s.closePopup({dismiss: t})
                    }

                    var i, n, o;
                    Gt.swalPromiseResolve.set(s, t), a.confirmButton.onclick = function () {
                        return ae(s, r)
                    }, a.cancelButton.onclick = function () {
                        return t = e, s.disableButtons(), void t(B.cancel);
                        var t
                    }, a.closeButton.onclick = function () {
                        return e(B.close)
                    }, de(s, a, e), le(s, Nt, r, e), (r.toast && (r.input || r.footer || r.showCloseButton) ? rt : lt)(document.body, R["toast-column"]), se(s, r), ne(r), i = Nt, n = r, o = e, Q(t = I()), n.timer && (i.timeout = new Jt(function () {
                        o("timer"), delete i.timeout
                    }, n.timer), n.timerProgressBar && (Z(t), setTimeout(function () {
                        i.timeout.running && et(n.timer)
                    }))), ue(a, r), a.container.scrollTop = 0
                })
            }, update: function (e) {
                var t = q(), i = vt.innerParams.get(this);
                if (!t || D(t, i.hideClass.popup)) return N("You're trying to update the closed or closing popup, that won't work. Use the update() method in preConfirm parameter or show a new popup.");
                var n = {};
                Object.keys(e).forEach(function (t) {
                    Ie.isUpdatableParameter(t) ? n[t] = e[t] : N('Invalid parameter to update: "'.concat(t, '". Updatable params are listed here: https://github.com/sweetalert2/sweetalert2/blob/master/src/utils/params.js'))
                });
                i = l({}, i, n);
                Tt(this, i), vt.innerParams.set(this, i), Object.defineProperties(this, {
                    params: {
                        value: l({}, this.params, e),
                        writable: !1,
                        enumerable: !0
                    }
                })
            }, _destroy: function () {
                var t = vt.domCache.get(this), e = vt.innerParams.get(this);
                e && (t.popup && Nt.swalCloseEventFinishedCallback && (Nt.swalCloseEventFinishedCallback(), delete Nt.swalCloseEventFinishedCallback), Nt.deferDisposalTimer && (clearTimeout(Nt.deferDisposalTimer), delete Nt.deferDisposalTimer), "function" == typeof e.onDestroy && e.onDestroy(), delete this.params, delete Nt.keydownHandler, delete Nt.keydownTarget, he(vt), he(Gt))
            }
        });

    function ke() {
        if ("undefined" != typeof window) {
            "undefined" == typeof Promise && p("This package requires a Promise library, please include a shim to enable it in this browser (See: https://github.com/sweetalert2/sweetalert2/wiki/Migration-from-SweetAlert-to-SweetAlert2#1-ie-support)"), pe = this;
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            var n = Object.freeze(this.constructor.argsToParams(e));
            Object.defineProperties(this, {params: {value: n, writable: !1, enumerable: !0, configurable: !0}});
            n = this._main(this.params);
            vt.promise.set(this, n)
        }
    }

    ke.prototype.then = function (t) {
        return vt.promise.get(this).then(t)
    }, ke.prototype.finally = function (t) {
        return vt.promise.get(this).finally(t)
    }, l(ke.prototype, Ee), l(ke, Wt), Object.keys(Ee).forEach(function (t) {
        ke[t] = function () {
            if (pe) return pe[t].apply(pe, arguments)
        }
    }), ke.DismissReason = B, ke.version = "9.10.8";
    var Ie = ke;
    return Ie.default = Ie
}), void 0 !== this && this.Sweetalert2 && (this.swal = this.sweetAlert = this.Swal = this.SweetAlert = this.Sweetalert2), function (h, d, p) {
    function r(t, e) {
        return typeof t === e
    }

    function f(t) {
        return "function" != typeof d.createElement ? d.createElement(t) : w ? d.createElementNS.call(d, "http://www.w3.org/2000/svg", t) : d.createElement.apply(d, arguments)
    }

    function l(t, e, i) {
        var n, o;
        for (o in t) if (t[o] in e) return !1 === i ? t[o] : (n = e[t[o]], r(n, "function") ? function (t, e) {
            return function () {
                return t.apply(e, arguments)
            }
        }(n, i || e) : n);
        return !1
    }

    function m(t) {
        return t.replace(/([A-Z])/g, function (t, e) {
            return "-" + e.toLowerCase()
        }).replace(/^ms-/, "-ms-")
    }

    function g(t, e, i, n) {
        var o, s, a, r = "modernizr", l = f("div"), c = ((a = d.body) || ((a = f(w ? "svg" : "body")).fake = !0), a);
        if (parseInt(i, 10)) for (; i--;) (o = f("div")).id = n ? n[i] : r + (i + 1), l.appendChild(o);
        return (a = f("style")).type = "text/css", a.id = "s" + r, (c.fake ? c : l).appendChild(a), c.appendChild(l), a.styleSheet ? a.styleSheet.cssText = t : a.appendChild(d.createTextNode(t)), l.id = r, c.fake && (c.style.background = "", c.style.overflow = "hidden", s = y.style.overflow, y.style.overflow = "hidden", y.appendChild(c)), t = e(l, t), c.fake ? (c.parentNode.removeChild(c), y.style.overflow = s, y.offsetHeight) : l.parentNode.removeChild(l), !!t
    }

    function c(t, e, i, n) {
        function o() {
            a && (delete S.style, delete S.modElem)
        }

        if (n = void 0 !== n && n, void 0 !== i) {
            var s = function (t, e) {
                var i = t.length;
                if ("CSS" in h && "supports" in h.CSS) {
                    for (; i--;) if (h.CSS.supports(m(t[i]), e)) return !0;
                    return !1
                }
                if ("CSSSupportsRule" in h) {
                    for (var n = []; i--;) n.push("(" + m(t[i]) + ":" + e + ")");
                    return g("@supports (" + (n = n.join(" or ")) + ") { #modernizr { position: absolute; } }", function (t) {
                        return "absolute" == getComputedStyle(t, null).position
                    })
                }
                return p
            }(t, i);
            if (void 0 !== s) return s
        }
        for (var a, r, l, c, d, u = ["modernizr", "tspan", "samp"]; !S.style && u.length;) a = !0, S.modElem = f(u.shift()), S.style = S.modElem.style;
        for (l = t.length, r = 0; r < l; r++) if (c = t[r], d = S.style[c], ~("" + c).indexOf("-") && (c = c.replace(/([a-z])-([a-z])/g, function (t, e, i) {
            return e + i.toUpperCase()
        }).replace(/^-/, "")), S.style[c] !== p) {
            if (n || void 0 === i) return o(), "pfx" != e || c;
            try {
                S.style[c] = i
            } catch (t) {
            }
            if (S.style[c] != d) return o(), "pfx" != e || c
        }
        return o(), !1
    }

    function n(t, e, i, n, o) {
        var s = t.charAt(0).toUpperCase() + t.slice(1), a = (t + " " + x.join(s + " ") + s).split(" ");
        return r(e, "string") || void 0 === e ? c(a, e, n, o) : l(a = (t + " " + _.join(s + " ") + s).split(" "), e, i)
    }

    function t(t, e, i) {
        return n(t, p, p, e, i)
    }

    var u = [], v = [], e = {
        _version: "3.3.1",
        _config: {classPrefix: "", enableClasses: !0, enableJSClass: !0, usePrefixes: !0},
        _q: [],
        on: function (t, e) {
            var i = this;
            setTimeout(function () {
                e(i[t])
            }, 0)
        },
        addTest: function (t, e, i) {
            v.push({name: t, fn: e, options: i})
        },
        addAsyncTest: function (t) {
            v.push({name: null, fn: t})
        }
    };
    (b = function () {
    }).prototype = e;
    var b = new b, y = d.documentElement, w = "svg" === y.nodeName.toLowerCase(), i = "Moz O ms Webkit",
        x = e._config.usePrefixes ? i.split(" ") : [];
    e._cssomPrefixes = x;
    var _ = e._config.usePrefixes ? i.toLowerCase().split(" ") : [];
    e._domPrefixes = _;
    var o = {elem: f("modernizr")};
    b._q.push(function () {
        delete o.elem
    });
    var s, a, C, S = {style: o.elem.style};
    b._q.unshift(function () {
        delete S.style
    }), e.testAllProps = n, e.testAllProps = t, b.addTest("csstransitions", t("transition", "all", !0)), function () {
        var t, e, i, n, o, s, a;
        for (a in v) if (v.hasOwnProperty(a)) {
            if (t = [], (e = v[a]).name && (t.push(e.name.toLowerCase()), e.options && e.options.aliases && e.options.aliases.length)) for (i = 0; i < e.options.aliases.length; i++) t.push(e.options.aliases[i].toLowerCase());
            for (n = r(e.fn, "function") ? e.fn() : e.fn, o = 0; o < t.length; o++) 1 === (s = t[o].split(".")).length ? b[s[0]] = n : (!b[s[0]] || b[s[0]] instanceof Boolean || (b[s[0]] = new Boolean(b[s[0]])), b[s[0]][s[1]] = n), u.push((n ? "" : "no-") + s.join("-"))
        }
    }(), s = u, a = y.className, C = b._config.classPrefix || "", w && (a = a.baseVal), b._config.enableJSClass && (i = new RegExp("(^|\\s)" + C + "no-js(\\s|$)"), a = a.replace(i, "$1" + C + "js$2")), b._config.enableClasses && (a += " " + C + s.join(" " + C), w ? y.className.baseVal = a : y.className = a), delete e.addTest, delete e.addAsyncTest;
    for (var T = 0; T < b._q.length; T++) b._q[T]();
    h.Modernizr = b
}(window, document), function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : (t = t || self).LazyLoad = e()
}(this, function () {
    "use strict";

    function e() {
        return (e = Object.assign || function (t) {
            for (var e = 1; e < arguments.length; e++) {
                var i, n = arguments[e];
                for (i in n) Object.prototype.hasOwnProperty.call(n, i) && (t[i] = n[i])
            }
            return t
        }).apply(this, arguments)
    }

    function a(t) {
        return e({}, I, t)
    }

    function o(t, e) {
        var i, e = new t(e);
        try {
            i = new CustomEvent("LazyLoad::Initialized", {detail: {instance: e}})
        } catch (t) {
            (i = document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized", !1, !1, {instance: e})
        }
        window.dispatchEvent(i)
    }

    function r(t) {
        return "native" === A(t)
    }

    function l(t, e) {
        t && (t.toLoadCount = e)
    }

    function i(t) {
        for (var e, i = [], n = 0; e = t.children[n]; n += 1) "SOURCE" === e.tagName && i.push(e);
        return i
    }

    function n(t, e, i) {
        i && t.setAttribute(e, i)
    }

    function s(t, e) {
        t.removeAttribute(e)
    }

    function c(t) {
        return !!t.llOriginalAttrs
    }

    function d(t) {
        var e;
        c(t) || ((e = {}).src = t.getAttribute("src"), e.srcset = t.getAttribute("srcset"), e.sizes = t.getAttribute("sizes"), t.llOriginalAttrs = e)
    }

    function u(t, e) {
        n(t, "sizes", O(t, e.data_sizes)), n(t, "srcset", O(t, e.data_srcset)), n(t, "src", O(t, e.data_src))
    }

    function h(t, e) {
        i(t).forEach(e)
    }

    function p(t, e) {
        var i = U[t.tagName];
        i && i(t, e)
    }

    function f(t, e) {
        var i = Y[t.tagName];
        i ? i(t, e) : (z(t = t, (e = e).data_bg, null), z(t, e.data_bg_hidpi, null))
    }

    function m(t, e) {
        !e || 0 < e.loadingCount || 0 < e.toLoadCount || N(t.callback_finish, e)
    }

    function g(t, e, i) {
        t.addEventListener(e, i), t.llEvLisnrs[e] = i
    }

    function v(t, e, i) {
        delete t.llTempImage, W(i, -1), i && --i.toLoadCount, j(t, e.class_loading), e.unobserve_completed && R(t, i)
    }

    function b(t) {
        return t.use_native && "loading" in HTMLImageElement.prototype
    }

    function y(t, c, d) {
        t.forEach(function (t) {
            return t.isIntersecting || 0 < t.intersectionRatio ? (s = t.target, a = t, r = c, l = d, P(s, "entered"), r.unobserve_entered && R(s, l), N(r.callback_enter, s, a, l), void (0 <= M.indexOf(A(s)) || et(s, r, l))) : (e = t.target, i = t, n = c, o = d, void (L(e) || (a = e, s = i, r = o, (l = n).cancel_on_exit && "loading" === A(a) && "IMG" === a.tagName && (J(a), V(t = a, function (t) {
                q(t)
            }), q(t), V(t = a, function (t) {
                F(t)
            }), F(t), j(a, l.class_loading), W(r, -1), D(a), N(l.callback_cancel, a, s, r)), N(n.callback_exit, e, i, o))));
            var e, i, n, o, s, a, r, l
        })
    }

    function w(t) {
        return Array.prototype.slice.call(t)
    }

    function x(t) {
        return t.container.querySelectorAll(t.elements_selector)
    }

    function _(t) {
        return "error" === A(t)
    }

    function C(t, e) {
        return e = t || x(e), w(e).filter(L)
    }

    function t(t, e) {
        var n, o, i, s, t = a(t);
        this._settings = t, this.loadingCount = 0, i = t, s = this, $ && !b(i) && (s._observer = new IntersectionObserver(function (t) {
            y(t, i, s)
        }, {
            root: i.container === document ? null : i.container,
            rootMargin: i.thresholds || i.threshold + "px"
        })), n = t, o = this, S && window.addEventListener("online", function () {
            var e, t, i;
            t = o, i = x(e = n), w(i).filter(_).forEach(function (t) {
                j(t, e.class_error), D(t)
            }), t.update()
        }), this.update(e)
    }

    var S = "undefined" != typeof window,
        T = S && !("onscroll" in window) || "undefined" != typeof navigator && /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),
        $ = S && "IntersectionObserver" in window, E = S && "classList" in document.createElement("p"),
        k = S && 1 < window.devicePixelRatio, I = {
            elements_selector: ".lazy",
            container: T || S ? document : null,
            threshold: 300,
            thresholds: null,
            data_src: "src",
            data_srcset: "srcset",
            data_sizes: "sizes",
            data_bg: "bg",
            data_bg_hidpi: "bg-hidpi",
            data_bg_multi: "bg-multi",
            data_bg_multi_hidpi: "bg-multi-hidpi",
            data_poster: "poster",
            class_applied: "applied",
            class_loading: "loading",
            class_loaded: "loaded",
            class_error: "error",
            unobserve_completed: !0,
            unobserve_entered: !1,
            cancel_on_exit: !0,
            callback_enter: null,
            callback_exit: null,
            callback_applied: null,
            callback_loading: null,
            callback_loaded: null,
            callback_error: null,
            callback_finish: null,
            callback_cancel: null,
            use_native: !1
        }, O = function (t, e) {
            return t.getAttribute("data-" + e)
        }, z = function (t, e, i) {
            e = "data-" + e;
            null !== i ? t.setAttribute(e, i) : t.removeAttribute(e)
        }, A = function (t) {
            return O(t, "ll-status")
        }, P = function (t, e) {
            return z(t, "ll-status", e)
        }, D = function (t) {
            return P(t, null)
        }, L = function (t) {
            return null === A(t)
        }, M = ["loading", "loaded", "applied", "error"], N = function (t, e, i, n) {
            t && (void 0 === n ? void 0 === i ? t(e) : t(e, i) : t(e, i, n))
        }, H = function (t, e) {
            E ? t.classList.add(e) : t.className += (t.className ? " " : "") + e
        }, j = function (t, e) {
            E ? t.classList.remove(e) : t.className = t.className.replace(new RegExp("(^|\\s+)" + e + "(\\s+|$)"), " ").replace(/^\s+/, "").replace(/\s+$/, "")
        }, B = function (t) {
            return t.llTempImage
        }, R = function (t, e) {
            !e || (e = e._observer) && e.unobserve(t)
        }, W = function (t, e) {
            t && (t.loadingCount += e)
        }, F = function (t) {
            var e;
            c(t) && (e = t.llOriginalAttrs, n(t, "src", e.src), n(t, "srcset", e.srcset), n(t, "sizes", e.sizes))
        }, q = function (t) {
            s(t, "src"), s(t, "srcset"), s(t, "sizes")
        }, V = function (t, e) {
            t = t.parentNode;
            t && "PICTURE" === t.tagName && i(t).forEach(e)
        }, U = {
            IMG: function (t, e) {
                V(t, function (t) {
                    d(t), u(t, e)
                }), d(t), u(t, e)
            }, IFRAME: function (t, e) {
                n(t, "src", O(t, e.data_src))
            }, VIDEO: function (t, e) {
                h(t, function (t) {
                    n(t, "src", O(t, e.data_src))
                }), n(t, "poster", O(t, e.data_poster)), n(t, "src", O(t, e.data_src)), t.load()
            }
        }, G = function (t, e, i) {
            W(i, 1), H(t, e.class_loading), P(t, "loading"), N(e.callback_loading, t, i)
        }, Y = {
            IMG: function (t, e) {
                z(t, e.data_src, null), z(t, e.data_srcset, null), z(t, e.data_sizes, null), V(t, function (t) {
                    z(t, e.data_srcset, null), z(t, e.data_sizes, null)
                })
            }, IFRAME: function (t, e) {
                z(t, e.data_src, null)
            }, VIDEO: function (t, e) {
                z(t, e.data_src, null), z(t, e.data_poster, null), h(t, function (t) {
                    z(t, e.data_src, null)
                })
            }
        }, X = function (t, e) {
            z(t, e.data_bg_multi, null), z(t, e.data_bg_multi_hidpi, null)
        }, K = ["IMG", "IFRAME", "VIDEO"], Z = function (t, e, i) {
            t.removeEventListener(e, i)
        }, Q = function (t) {
            return !!t.llEvLisnrs
        }, J = function (t) {
            if (Q(t)) {
                var e, i = t.llEvLisnrs;
                for (e in i) {
                    var n = i[e];
                    Z(t, e, n)
                }
                delete t.llEvLisnrs
            }
        }, tt = function (i, n, o) {
            var s = B(i) || i;
            Q(s) || function (t) {
                Q(t) || (t.llEvLisnrs = {});
                var e = "VIDEO" === t.tagName ? "loadeddata" : "load";
                g(t, e, function (t) {
                    !function (t, e, i, n) {
                        var o = r(e);
                        v(e, i, n), H(e, i.class_loaded), P(e, "loaded"), f(e, i), N(i.callback_loaded, e, n), o || m(i, n)
                    }(0, i, n, o), J(s)
                }), g(t, "error", function (t) {
                    !function (t, e, i, n) {
                        var o = r(e);
                        v(e, i, n), H(e, i.class_error), P(e, "error"), N(i.callback_error, e, n), o || m(i, n)
                    }(0, i, n, o), J(s)
                })
            }(s)
        }, et = function (t, e, i) {
            var n, o, s, a, r;
            -1 < K.indexOf(t.tagName) ? (tt(n = t, r = e, a = i), p(n, r), G(n, r, a)) : (o = e, s = i, (n = t).llTempImage = document.createElement("IMG"), tt(n, o, s), r = s, i = O(a = n, (e = o).data_bg), t = O(a, e.data_bg_hidpi), (i = k && t ? t : i) && (a.style.backgroundImage = 'url("'.concat(i, '")'), B(a).setAttribute("src", i), G(a, e, r)), e = s, n = O(r = n, (s = o).data_bg_multi), o = O(r, s.data_bg_multi_hidpi), (n = k && o ? o : n) && (r.style.backgroundImage = n, e = e, H(r = r, (s = s).class_applied), P(r, "applied"), X(r, s), s.unobserve_completed && R(r, s), N(s.callback_applied, r, e)))
        }, it = ["IMG", "IFRAME"];
    return t.prototype = {
        update: function (t) {
            var e, i, n, o = this._settings, s = C(t, o);
            l(this, s.length), !T && $ ? b(o) ? (i = o, n = this, s.forEach(function (t) {
                var e;
                -1 !== it.indexOf(t.tagName) && (t.setAttribute("loading", "lazy"), tt(e = t, t = i, n), p(e, t), f(e, t), P(e, "native"))
            }), l(n, 0)) : (t = s, (o = this._observer).disconnect(), e = o, t.forEach(function (t) {
                e.observe(t)
            })) : this.loadAll(s)
        }, destroy: function () {
            this._observer && this._observer.disconnect(), x(this._settings).forEach(function (t) {
                delete t.llOriginalAttrs
            }), delete this._observer, delete this._settings, delete this.loadingCount, delete this.toLoadCount
        }, loadAll: function (t) {
            var e = this, i = this._settings;
            C(t, i).forEach(function (t) {
                R(t, e), et(t, i, e)
            })
        }
    }, t.load = function (t, e) {
        e = a(e);
        et(t, e)
    }, t.resetStatus = function (t) {
        D(t)
    }, S && function (t, e) {
        if (e) if (e.length) for (var i, n = 0; i = e[n]; n += 1) o(t, i); else o(t, e)
    }(t, window.lazyLoadOptions), t
}), function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], function (t) {
        e(t, window, document)
    }) : "object" == typeof module && module.exports ? module.exports = e(require("jquery"), window, document) : e(jQuery, window, document)
}(function (a, s, t, r) {
    "use strict";
    var l = "intlTelInput", i = 1, n = {
        allowDropdown: !0,
        autoHideDialCode: !0,
        autoPlaceholder: !0,
        customPlaceholder: null,
        dropdownContainer: "",
        excludeCountries: [],
        formatOnInit: !0,
        geoIpLookup: null,
        initialCountry: "",
        nationalMode: !0,
        numberType: "MOBILE",
        onlyCountries: [],
        preferredCountries: ["us", "gb"],
        separateDialCode: !1,
        utilsScript: ""
    }, c = 38, d = 40, u = 13, h = 27, e = 43, p = 65, f = 90, m = 32, o = 9;

    function g(t, e) {
        this.telInput = a(t), this.options = a.extend({}, n, e), this.ns = "." + l + i++, this.isGoodBrowser = Boolean(t.setSelectionRange), this.hadInitialPlaceholder = Boolean(a(t).attr("placeholder"))
    }

    a(s).on("load", function () {
        a.fn[l].windowLoaded = !0
    }), g.prototype = {
        _init: function () {
            return this.options.nationalMode && (this.options.autoHideDialCode = !1), this.options.separateDialCode && (this.options.autoHideDialCode = this.options.nationalMode = !1, this.options.allowDropdown = !0), this.isMobile = /Android.+Mobile|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent), this.isMobile && (a("body").addClass("iti-mobile"), this.options.dropdownContainer || (this.options.dropdownContainer = "body")), this.autoCountryDeferred = new a.Deferred, this.utilsScriptDeferred = new a.Deferred, this._processCountryData(), this._generateMarkup(), this._setInitialState(), this._initListeners(), this._initRequests(), [this.autoCountryDeferred, this.utilsScriptDeferred]
        }, _processCountryData: function () {
            this._processAllCountries(), this._processCountryCodes(), this._processPreferredCountries()
        }, _addCountryCode: function (t, e, i) {
            e in this.countryCodes || (this.countryCodes[e] = []), this.countryCodes[e][i || 0] = t
        }, _filterCountries: function (t, e) {
            for (var i = 0; i < t.length; i++) t[i] = t[i].toLowerCase();
            for (this.countries = [], i = 0; i < v.length; i++) e(a.inArray(v[i].iso2, t)) && this.countries.push(v[i])
        }, _processAllCountries: function () {
            this.options.onlyCountries.length ? this._filterCountries(this.options.onlyCountries, function (t) {
                return -1 != t
            }) : this.options.excludeCountries.length ? this._filterCountries(this.options.excludeCountries, function (t) {
                return -1 == t
            }) : this.countries = v
        }, _processCountryCodes: function () {
            this.countryCodes = {};
            for (var t = 0; t < this.countries.length; t++) {
                var e = this.countries[t];
                if (this._addCountryCode(e.iso2, e.dialCode, e.priority), e.areaCodes) for (var i = 0; i < e.areaCodes.length; i++) this._addCountryCode(e.iso2, e.dialCode + e.areaCodes[i])
            }
        }, _processPreferredCountries: function () {
            this.preferredCountries = [];
            for (var t = 0; t < this.options.preferredCountries.length; t++) {
                var e = this.options.preferredCountries[t].toLowerCase(), e = this._getCountryData(e, !1, !0);
                e && this.preferredCountries.push(e)
            }
        }, _generateMarkup: function () {
            this.telInput.attr("autocomplete", "off");
            var t = "intl-tel-input";
            this.options.allowDropdown && (t += " allow-dropdown"), this.options.separateDialCode && (t += " separate-dial-code"), this.telInput.wrap(a("<div>", {class: t})), this.flagsContainer = a("<div>", {class: "flag-container"}).insertBefore(this.telInput);
            t = a("<div>", {class: "selected-flag"});
            t.appendTo(this.flagsContainer), this.selectedFlagInner = a("<div>", {class: "iti-flag"}).appendTo(t), this.options.separateDialCode && (this.selectedDialCode = a("<div>", {class: "selected-dial-code"}).appendTo(t)), this.options.allowDropdown ? (t.attr("tabindex", "0"), a("<div>", {class: "iti-arrow"}).appendTo(t), this.countryList = a("<ul>", {class: "country-list hide"}), this.preferredCountries.length && (this._appendListItems(this.preferredCountries, "preferred"), a("<li>", {class: "divider"}).appendTo(this.countryList)), this._appendListItems(this.countries, ""), this.countryListItems = this.countryList.children(".country"), this.options.dropdownContainer ? this.dropdown = a("<div>", {class: "intl-tel-input iti-container"}).append(this.countryList) : this.countryList.appendTo(this.flagsContainer)) : this.countryListItems = a()
        }, _appendListItems: function (t, e) {
            for (var i = "", n = 0; n < t.length; n++) {
                var o = t[n];
                i += "<li class='country " + e + "' data-dial-code='" + o.dialCode + "' data-country-code='" + o.iso2 + "'>", i += "<div class='flag-box'><div class='iti-flag " + o.iso2 + "'></div></div>", i += "<span class='country-name'>" + o.name + "</span>", i += "<span class='dial-code'>+" + o.dialCode + "</span>", i += "</li>"
            }
            this.countryList.append(i)
        }, _setInitialState: function () {
            var t = this.telInput.val();
            this._getDialCode(t) ? this._updateFlagFromNumber(t, !0) : "auto" !== this.options.initialCountry && (this.options.initialCountry ? this._setFlag(this.options.initialCountry, !0) : (this.defaultCountry = (this.preferredCountries.length ? this.preferredCountries : this.countries)[0].iso2, t || this._setFlag(this.defaultCountry, !0)), t || this.options.nationalMode || this.options.autoHideDialCode || this.options.separateDialCode || this.telInput.val("+" + this.selectedCountryData.dialCode)), t && this._updateValFromNumber(t, this.options.formatOnInit)
        }, _initListeners: function () {
            this._initKeyListeners(), this.options.autoHideDialCode && this._initFocusListeners(), this.options.allowDropdown && this._initDropdownListeners()
        }, _initDropdownListeners: function () {
            var e = this, t = this.telInput.closest("label");
            t.length && t.on("click" + this.ns, function (t) {
                e.countryList.hasClass("hide") ? e.telInput.focus() : t.preventDefault()
            }), this.selectedFlagInner.parent().on("click" + this.ns, function (t) {
                !e.countryList.hasClass("hide") || e.telInput.prop("disabled") || e.telInput.prop("readonly") || e._showDropdown()
            }), this.flagsContainer.on("keydown" + e.ns, function (t) {
                !e.countryList.hasClass("hide") || t.which != c && t.which != d && t.which != m && t.which != u || (t.preventDefault(), t.stopPropagation(), e._showDropdown()), t.which == o && e._closeDropdown()
            })
        }, _initRequests: function () {
            var t = this;
            this.options.utilsScript ? a.fn[l].windowLoaded ? a.fn[l].loadUtils(this.options.utilsScript, this.utilsScriptDeferred) : a(s).on("load", function () {
                a.fn[l].loadUtils(t.options.utilsScript, t.utilsScriptDeferred)
            }) : this.utilsScriptDeferred.resolve(), "auto" === this.options.initialCountry ? this._loadAutoCountry() : this.autoCountryDeferred.resolve()
        }, _loadAutoCountry: function () {
            var t = s.Cookies ? Cookies.get("itiAutoCountry") : "";
            t && (a.fn[l].autoCountry = t), a.fn[l].autoCountry ? this.handleAutoCountry() : a.fn[l].startedLoadingAutoCountry || (a.fn[l].startedLoadingAutoCountry = !0, "function" == typeof this.options.geoIpLookup && this.options.geoIpLookup(function (t) {
                a.fn[l].autoCountry = t.toLowerCase(), s.Cookies && Cookies.set("itiAutoCountry", a.fn[l].autoCountry, {path: "/"}), setTimeout(function () {
                    a(".intl-tel-input input").intlTelInput("handleAutoCountry")
                })
            }))
        }, _initKeyListeners: function () {
            var t = this;
            this.telInput.on("keyup" + this.ns, function () {
                t._updateFlagFromNumber(t.telInput.val())
            }), this.telInput.on("cut" + this.ns + " paste" + this.ns + " keyup" + this.ns, function () {
                setTimeout(function () {
                    t._updateFlagFromNumber(t.telInput.val())
                })
            })
        }, _cap: function (t) {
            var e = this.telInput.attr("maxlength");
            return e && t.length > e ? t.substr(0, e) : t
        }, _initFocusListeners: function () {
            var i = this;
            this.telInput.on("mousedown" + this.ns, function (t) {
                i.telInput.is(":focus") || i.telInput.val() || (t.preventDefault(), i.telInput.focus())
            }), this.telInput.on("focus" + this.ns, function (t) {
                i.telInput.val() || i.telInput.prop("readonly") || !i.selectedCountryData.dialCode || (i.telInput.val("+" + i.selectedCountryData.dialCode), i.telInput.one("keypress.plus" + i.ns, function (t) {
                    t.which == e && i.telInput.val("")
                }), setTimeout(function () {
                    var t, e = i.telInput[0];
                    i.isGoodBrowser && (t = i.telInput.val().length, e.setSelectionRange(t, t))
                }))
            }), this.telInput.on("blur" + this.ns, function () {
                var t = i.telInput.val();
                "+" == t.charAt(0) && ((t = i._getNumeric(t)) && i.selectedCountryData.dialCode != t || i.telInput.val("")), i.telInput.off("keypress.plus" + i.ns)
            })
        }, _getNumeric: function (t) {
            return t.replace(/\D/g, "")
        }, _showDropdown: function () {
            this._setDropdownPosition();
            var t = this.countryList.children(".active");
            t.length && (this._highlightListItem(t), this._scrollTo(t)), this._bindDropdownListeners(), this.selectedFlagInner.children(".iti-arrow").addClass("up")
        }, _setDropdownPosition: function () {
            var t, e, i, n, o = this;
            this.options.dropdownContainer && this.dropdown.appendTo(this.options.dropdownContainer), this.dropdownHeight = this.countryList.removeClass("hide").outerHeight(), this.isMobile || (e = (t = this.telInput.offset()).top, n = a(s).scrollTop(), i = e + this.telInput.outerHeight() + this.dropdownHeight < n + a(s).height(), n = e - this.dropdownHeight > n, this.countryList.toggleClass("dropup", !i && n), this.options.dropdownContainer && (n = !i && n ? 0 : this.telInput.innerHeight(), this.dropdown.css({
                top: e + n,
                left: t.left
            }), a(s).on("scroll" + this.ns, function () {
                o._closeDropdown()
            })))
        }, _bindDropdownListeners: function () {
            var e = this;
            this.countryList.on("mouseover" + this.ns, ".country", function (t) {
                e._highlightListItem(a(this))
            }), this.countryList.on("click" + this.ns, ".country", function (t) {
                e._selectListItem(a(this))
            });
            var i = !0;
            a("html").on("click" + this.ns, function (t) {
                i || e._closeDropdown(), i = !1
            });
            var n = "", o = null;
            a(t).on("keydown" + this.ns, function (t) {
                t.preventDefault(), t.which == c || t.which == d ? e._handleUpDownKey(t.which) : t.which == u ? e._handleEnterKey() : t.which == h ? e._closeDropdown() : (t.which >= p && t.which <= f || t.which == m) && (o && clearTimeout(o), n += String.fromCharCode(t.which), e._searchForCountry(n), o = setTimeout(function () {
                    n = ""
                }, 1e3))
            })
        }, _handleUpDownKey: function (t) {
            var e = this.countryList.children(".highlight").first(), e = t == c ? e.prev() : e.next();
            e.length && (e.hasClass("divider") && (e = t == c ? e.prev() : e.next()), this._highlightListItem(e), this._scrollTo(e))
        }, _handleEnterKey: function () {
            var t = this.countryList.children(".highlight").first();
            t.length && this._selectListItem(t)
        }, _searchForCountry: function (t) {
            for (var e = 0; e < this.countries.length; e++) if (this._startsWith(this.countries[e].name, t)) {
                var i = this.countryList.children("[data-country-code=" + this.countries[e].iso2 + "]").not(".preferred");
                this._highlightListItem(i), this._scrollTo(i, !0);
                break
            }
        }, _startsWith: function (t, e) {
            return t.substr(0, e.length).toUpperCase() == e
        }, _updateValFromNumber: function (t, e, i) {
            e && s.intlTelInputUtils && this.selectedCountryData && (a.isNumeric(i) || (i = this.options.nationalMode || "+" != t.charAt(0) ? intlTelInputUtils.numberFormat.NATIONAL : intlTelInputUtils.numberFormat.INTERNATIONAL), t = intlTelInputUtils.formatNumber(t, this.selectedCountryData.iso2, i)), t = this._beforeSetNumber(t), this.telInput.val(t)
        }, _updateFlagFromNumber: function (t, e) {
            t && this.options.nationalMode && this.selectedCountryData && "1" == this.selectedCountryData.dialCode && "+" != t.charAt(0) && (t = "+" + (t = "1" != t.charAt(0) ? "1" + t : t));
            var i = this._getDialCode(t), n = null;
            if (i) {
                var o = this.countryCodes[this._getNumeric(i)];
                if (!(this.selectedCountryData && -1 != a.inArray(this.selectedCountryData.iso2, o)) || this._isUnknownNanp(t, i)) for (var s = 0; s < o.length; s++) if (o[s]) {
                    n = o[s];
                    break
                }
            } else "+" == t.charAt(0) && this._getNumeric(t).length ? n = "" : t && "+" != t || (n = this.defaultCountry);
            null !== n && this._setFlag(n, e)
        }, _isUnknownNanp: function (t, e) {
            return "+1" == e && 4 <= this._getNumeric(t).length
        }, _highlightListItem: function (t) {
            this.countryListItems.removeClass("highlight"), t.addClass("highlight")
        }, _getCountryData: function (t, e, i) {
            for (var n = e ? v : this.countries, o = 0; o < n.length; o++) if (n[o].iso2 == t) return n[o];
            if (i) return null;
            throw new Error("No country data for '" + t + "'")
        }, _setFlag: function (t, e) {
            var i = this.selectedCountryData && this.selectedCountryData.iso2 ? this.selectedCountryData : {};
            this.selectedCountryData = t ? this._getCountryData(t, !1, !1) : {}, this.selectedCountryData.iso2 && (this.defaultCountry = this.selectedCountryData.iso2), this.selectedFlagInner.attr("class", "iti-flag " + t);
            var n, o = t ? this.selectedCountryData.name + ": +" + this.selectedCountryData.dialCode : "Unknown";
            this.selectedFlagInner.parent().attr("title", o), this.options.separateDialCode && (n = this.selectedCountryData.dialCode ? "+" + this.selectedCountryData.dialCode : "", o = this.telInput.parent(), i.dialCode && o.removeClass("iti-sdc-" + (i.dialCode.length + 1)), n && o.addClass("iti-sdc-" + n.length), this.selectedDialCode.text(n)), this._updatePlaceholder(), this.countryListItems.removeClass("active"), t && this.countryListItems.find(".iti-flag." + t).first().closest(".country").addClass("active"), e || i.iso2 === t || this.telInput.trigger("countrychange", this.selectedCountryData)
        }, _updatePlaceholder: function () {
            var t;
            s.intlTelInputUtils && !this.hadInitialPlaceholder && this.options.autoPlaceholder && this.selectedCountryData && (t = intlTelInputUtils.numberType[this.options.numberType], t = this.selectedCountryData.iso2 ? intlTelInputUtils.getExampleNumber(this.selectedCountryData.iso2, this.options.nationalMode, t) : "", t = this._beforeSetNumber(t), "function" == typeof this.options.customPlaceholder && (t = this.options.customPlaceholder(t, this.selectedCountryData)), this.telInput.attr("placeholder", t))
        }, _selectListItem: function (t) {
            this._setFlag(t.attr("data-country-code")), this._closeDropdown(), this._updateDialCode(t.attr("data-dial-code"), !0), this.telInput.focus(), this.isGoodBrowser && (t = this.telInput.val().length, this.telInput[0].setSelectionRange(t, t))
        }, _closeDropdown: function () {
            this.countryList.addClass("hide"), this.selectedFlagInner.children(".iti-arrow").removeClass("up"), a(t).off(this.ns), a("html").off(this.ns), this.countryList.off(this.ns), this.options.dropdownContainer && (this.isMobile || a(s).off("scroll" + this.ns), this.dropdown.detach())
        }, _scrollTo: function (t, e) {
            var i = this.countryList, n = i.height(), o = i.offset().top, s = o + n, a = t.outerHeight(),
                r = t.offset().top, l = r + a, c = r - o + i.scrollTop(), t = n / 2 - a / 2;
            r < o ? (e && (c -= t), i.scrollTop(c)) : s < l && (e && (c += t), i.scrollTop(c - (n - a)))
        }, _updateDialCode: function (t, e) {
            var i = this.telInput.val();
            if (t = "+" + t, "+" == i.charAt(0)) var n = this._getDialCode(i), n = n ? i.replace(n, t) : t; else {
                if (this.options.nationalMode || this.options.separateDialCode) return;
                if (i) n = t + i; else {
                    if (!e && this.options.autoHideDialCode) return;
                    n = t
                }
            }
            this.telInput.val(n)
        }, _getDialCode: function (t) {
            var e = "";
            if ("+" == t.charAt(0)) for (var i = "", n = 0; n < t.length; n++) {
                var o = t.charAt(n);
                if (a.isNumeric(o) && (i += o, this.countryCodes[i] && (e = t.substr(0, n + 1)), 4 == i.length)) break
            }
            return e
        }, _getFullNumber: function () {
            return (this.options.separateDialCode ? "+" + this.selectedCountryData.dialCode : "") + this.telInput.val()
        }, _beforeSetNumber: function (t) {
            var e;
            return !this.options.separateDialCode || (e = this._getDialCode(t)) && (e = " " === t[(e = null !== this.selectedCountryData.areaCodes ? "+" + this.selectedCountryData.dialCode : e).length] || "-" === t[e.length] ? e.length + 1 : e.length, t = t.substr(e)), this._cap(t)
        }, handleAutoCountry: function () {
            "auto" === this.options.initialCountry && (this.defaultCountry = a.fn[l].autoCountry, this.telInput.val() || this.setCountry(this.defaultCountry), this.autoCountryDeferred.resolve())
        }, destroy: function () {
            this.allowDropdown && (this._closeDropdown(), this.selectedFlagInner.parent().off(this.ns), this.telInput.closest("label").off(this.ns)), this.telInput.off(this.ns), this.telInput.parent().before(this.telInput).remove()
        }, getExtension: function () {
            return s.intlTelInputUtils ? intlTelInputUtils.getExtension(this._getFullNumber(), this.selectedCountryData.iso2) : ""
        }, getNumber: function (t) {
            return s.intlTelInputUtils ? intlTelInputUtils.formatNumber(this._getFullNumber(), this.selectedCountryData.iso2, t) : ""
        }, getNumberType: function () {
            return s.intlTelInputUtils ? intlTelInputUtils.getNumberType(this._getFullNumber(), this.selectedCountryData.iso2) : -99
        }, getSelectedCountryData: function () {
            return this.selectedCountryData || {}
        }, getValidationError: function () {
            return s.intlTelInputUtils ? intlTelInputUtils.getValidationError(this._getFullNumber(), this.selectedCountryData.iso2) : -99
        }, isValidNumber: function () {
            var t = a.trim(this._getFullNumber()), e = this.options.nationalMode ? this.selectedCountryData.iso2 : "";
            return s.intlTelInputUtils ? intlTelInputUtils.isValidNumber(t, e) : null
        }, setCountry: function (t) {
            t = t.toLowerCase(), this.selectedFlagInner.hasClass(t) || (this._setFlag(t), this._updateDialCode(this.selectedCountryData.dialCode, !1))
        }, setNumber: function (t, e) {
            this._updateFlagFromNumber(t), this._updateValFromNumber(t, a.isNumeric(e), e)
        }, handleUtils: function () {
            s.intlTelInputUtils && (this.telInput.val() && this._updateValFromNumber(this.telInput.val(), this.options.formatOnInit), this._updatePlaceholder()), this.utilsScriptDeferred.resolve()
        }
    }, a.fn[l] = function (i) {
        var e, n = arguments;
        if (i === r || "object" == typeof i) {
            var o = [];
            return this.each(function () {
                var t, e;
                a.data(this, "plugin_" + l) || (e = (t = new g(this, i))._init(), o.push(e[0]), o.push(e[1]), a.data(this, "plugin_" + l, t))
            }), a.when.apply(null, o)
        }
        if ("string" == typeof i && "_" !== i[0]) return this.each(function () {
            var t = a.data(this, "plugin_" + l);
            t instanceof g && "function" == typeof t[i] && (e = t[i].apply(t, Array.prototype.slice.call(n, 1))), "destroy" === i && a.data(this, "plugin_" + l, null)
        }), e !== r ? e : this
    }, a.fn[l].getCountryData = function () {
        return v
    }, a.fn[l].loadUtils = function (t, e) {
        a.fn[l].loadedUtilsScript ? e && e.resolve() : (a.fn[l].loadedUtilsScript = !0, a.ajax({
            url: t,
            complete: function () {
                a(".intl-tel-input input").intlTelInput("handleUtils")
            },
            dataType: "script",
            cache: !0
        }))
    }, a.fn[l].version = "8.4.7";
    for (var v = [["Afghanistan (‫افغانستان‬‎)", "af", "93"], ["Albania (Shqipëri)", "al", "355"], ["Algeria (‫الجزائر‬‎)", "dz", "213"], ["American Samoa", "as", "1684"], ["Andorra", "ad", "376"], ["Angola", "ao", "244"], ["Anguilla", "ai", "1264"], ["Antigua and Barbuda", "ag", "1268"], ["Argentina", "ar", "54"], ["Armenia (Հայաստան)", "am", "374"], ["Aruba", "aw", "297"], ["Australia", "au", "61", 0], ["Austria (Österreich)", "at", "43"], ["Azerbaijan (Azərbaycan)", "az", "994"], ["Bahamas", "bs", "1242"], ["Bahrain (‫البحرين‬‎)", "bh", "973"], ["Bangladesh (বাংলাদেশ)", "bd", "880"], ["Barbados", "bb", "1246"], ["Belarus (Беларусь)", "by", "375"], ["Belgium (België)", "be", "32"], ["Belize", "bz", "501"], ["Benin (Bénin)", "bj", "229"], ["Bermuda", "bm", "1441"], ["Bhutan (འབྲུག)", "bt", "975"], ["Bolivia", "bo", "591"], ["Bosnia and Herzegovina (Босна и Херцеговина)", "ba", "387"], ["Botswana", "bw", "267"], ["Brazil (Brasil)", "br", "55"], ["British Indian Ocean Territory", "io", "246"], ["British Virgin Islands", "vg", "1284"], ["Brunei", "bn", "673"], ["Bulgaria (България)", "bg", "359"], ["Burkina Faso", "bf", "226"], ["Burundi (Uburundi)", "bi", "257"], ["Cambodia (កម្ពុជា)", "kh", "855"], ["Cameroon (Cameroun)", "cm", "237"], ["Canada", "ca", "1", 1, ["204", "226", "236", "249", "250", "289", "306", "343", "365", "387", "403", "416", "418", "431", "437", "438", "450", "506", "514", "519", "548", "579", "581", "587", "604", "613", "639", "647", "672", "705", "709", "742", "778", "780", "782", "807", "819", "825", "867", "873", "902", "905"]], ["Cape Verde (Kabu Verdi)", "cv", "238"], ["Caribbean Netherlands", "bq", "599", 1], ["Cayman Islands", "ky", "1345"], ["Central African Republic (République centrafricaine)", "cf", "236"], ["Chad (Tchad)", "td", "235"], ["Chile", "cl", "56"], ["China (中国)", "cn", "86"], ["Christmas Island", "cx", "61", 2], ["Cocos (Keeling) Islands", "cc", "61", 1], ["Colombia", "co", "57"], ["Comoros (‫جزر القمر‬‎)", "km", "269"], ["Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)", "cd", "243"], ["Congo (Republic) (Congo-Brazzaville)", "cg", "242"], ["Cook Islands", "ck", "682"], ["Costa Rica", "cr", "506"], ["Côte d’Ivoire", "ci", "225"], ["Croatia (Hrvatska)", "hr", "385"], ["Cuba", "cu", "53"], ["Curaçao", "cw", "599", 0], ["Cyprus (Κύπρος)", "cy", "357"], ["Czech Republic (Česká republika)", "cz", "420"], ["Denmark (Danmark)", "dk", "45"], ["Djibouti", "dj", "253"], ["Dominica", "dm", "1767"], ["Dominican Republic (República Dominicana)", "do", "1", 2, ["809", "829", "849"]], ["Ecuador", "ec", "593"], ["Egypt (‫مصر‬‎)", "eg", "20"], ["El Salvador", "sv", "503"], ["Equatorial Guinea (Guinea Ecuatorial)", "gq", "240"], ["Eritrea", "er", "291"], ["Estonia (Eesti)", "ee", "372"], ["Ethiopia", "et", "251"], ["Falkland Islands (Islas Malvinas)", "fk", "500"], ["Faroe Islands (Føroyar)", "fo", "298"], ["Fiji", "fj", "679"], ["Finland (Suomi)", "fi", "358", 0], ["France", "fr", "33"], ["French Guiana (Guyane française)", "gf", "594"], ["French Polynesia (Polynésie française)", "pf", "689"], ["Gabon", "ga", "241"], ["Gambia", "gm", "220"], ["Georgia (საქართველო)", "ge", "995"], ["Germany (Deutschland)", "de", "49"], ["Ghana (Gaana)", "gh", "233"], ["Gibraltar", "gi", "350"], ["Greece (Ελλάδα)", "gr", "30"], ["Greenland (Kalaallit Nunaat)", "gl", "299"], ["Grenada", "gd", "1473"], ["Guadeloupe", "gp", "590", 0], ["Guam", "gu", "1671"], ["Guatemala", "gt", "502"], ["Guernsey", "gg", "44", 1], ["Guinea (Guinée)", "gn", "224"], ["Guinea-Bissau (Guiné Bissau)", "gw", "245"], ["Guyana", "gy", "592"], ["Haiti", "ht", "509"], ["Honduras", "hn", "504"], ["Hong Kong (香港)", "hk", "852"], ["Hungary (Magyarország)", "hu", "36"], ["Iceland (Ísland)", "is", "354"], ["India (भारत)", "in", "91"], ["Indonesia", "id", "62"], ["Iran (‫ایران‬‎)", "ir", "98"], ["Iraq (‫العراق‬‎)", "iq", "964"], ["Ireland", "ie", "353"], ["Isle of Man", "im", "44", 2], ["Israel (‫ישראל‬‎)", "il", "972"], ["Italy (Italia)", "it", "39", 0], ["Jamaica", "jm", "1876"], ["Japan (日本)", "jp", "81"], ["Jersey", "je", "44", 3], ["Jordan (‫الأردن‬‎)", "jo", "962"], ["Kazakhstan (Казахстан)", "kz", "7", 1], ["Kenya", "ke", "254"], ["Kiribati", "ki", "686"], ["Kuwait (‫الكويت‬‎)", "kw", "965"], ["Kyrgyzstan (Кыргызстан)", "kg", "996"], ["Laos (ລາວ)", "la", "856"], ["Latvia (Latvija)", "lv", "371"], ["Lebanon (‫لبنان‬‎)", "lb", "961"], ["Lesotho", "ls", "266"], ["Liberia", "lr", "231"], ["Libya (‫ليبيا‬‎)", "ly", "218"], ["Liechtenstein", "li", "423"], ["Lithuania (Lietuva)", "lt", "370"], ["Luxembourg", "lu", "352"], ["Macau (澳門)", "mo", "853"], ["Macedonia (FYROM) (Македонија)", "mk", "389"], ["Madagascar (Madagasikara)", "mg", "261"], ["Malawi", "mw", "265"], ["Malaysia", "my", "60"], ["Maldives", "mv", "960"], ["Mali", "ml", "223"], ["Malta", "mt", "356"], ["Marshall Islands", "mh", "692"], ["Martinique", "mq", "596"], ["Mauritania (‫موريتانيا‬‎)", "mr", "222"], ["Mauritius (Moris)", "mu", "230"], ["Mayotte", "yt", "262", 1], ["Mexico (México)", "mx", "52"], ["Micronesia", "fm", "691"], ["Moldova (Republica Moldova)", "md", "373"], ["Monaco", "mc", "377"], ["Mongolia (Монгол)", "mn", "976"], ["Montenegro (Crna Gora)", "me", "382"], ["Montserrat", "ms", "1664"], ["Morocco (‫المغرب‬‎)", "ma", "212", 0], ["Mozambique (Moçambique)", "mz", "258"], ["Myanmar (Burma) (မြန်မာ)", "mm", "95"], ["Namibia (Namibië)", "na", "264"], ["Nauru", "nr", "674"], ["Nepal (नेपाल)", "np", "977"], ["Netherlands (Nederland)", "nl", "31"], ["New Caledonia (Nouvelle-Calédonie)", "nc", "687"], ["New Zealand", "nz", "64"], ["Nicaragua", "ni", "505"], ["Niger (Nijar)", "ne", "227"], ["Nigeria", "ng", "234"], ["Niue", "nu", "683"], ["Norfolk Island", "nf", "672"], ["North Korea (조선 민주주의 인민 공화국)", "kp", "850"], ["Northern Mariana Islands", "mp", "1670"], ["Norway (Norge)", "no", "47", 0], ["Oman (‫عُمان‬‎)", "om", "968"], ["Pakistan (‫پاکستان‬‎)", "pk", "92"], ["Palau", "pw", "680"], ["Palestine (‫فلسطين‬‎)", "ps", "970"], ["Panama (Panamá)", "pa", "507"], ["Papua New Guinea", "pg", "675"], ["Paraguay", "py", "595"], ["Peru (Perú)", "pe", "51"], ["Philippines", "ph", "63"], ["Poland (Polska)", "pl", "48"], ["Portugal", "pt", "351"], ["Puerto Rico", "pr", "1", 3, ["787", "939"]], ["Qatar (‫قطر‬‎)", "qa", "974"], ["Réunion (La Réunion)", "re", "262", 0], ["Romania (România)", "ro", "40"], ["Russia (Россия)", "ru", "7", 0], ["Rwanda", "rw", "250"], ["Saint Barthélemy (Saint-Barthélemy)", "bl", "590", 1], ["Saint Helena", "sh", "290"], ["Saint Kitts and Nevis", "kn", "1869"], ["Saint Lucia", "lc", "1758"], ["Saint Martin (Saint-Martin (partie française))", "mf", "590", 2], ["Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)", "pm", "508"], ["Saint Vincent and the Grenadines", "vc", "1784"], ["Samoa", "ws", "685"], ["San Marino", "sm", "378"], ["São Tomé and Príncipe (São Tomé e Príncipe)", "st", "239"], ["Saudi Arabia (‫المملكة العربية السعودية‬‎)", "sa", "966"], ["Senegal (Sénégal)", "sn", "221"], ["Serbia (Србија)", "rs", "381"], ["Seychelles", "sc", "248"], ["Sierra Leone", "sl", "232"], ["Singapore", "sg", "65"], ["Sint Maarten", "sx", "1721"], ["Slovakia (Slovensko)", "sk", "421"], ["Slovenia (Slovenija)", "si", "386"], ["Solomon Islands", "sb", "677"], ["Somalia (Soomaaliya)", "so", "252"], ["South Africa", "za", "27"], ["South Korea (대한민국)", "kr", "82"], ["South Sudan (‫جنوب السودان‬‎)", "ss", "211"], ["Spain (España)", "es", "34"], ["Sri Lanka (ශ්‍රී ලංකාව)", "lk", "94"], ["Sudan (‫السودان‬‎)", "sd", "249"], ["Suriname", "sr", "597"], ["Svalbard and Jan Mayen", "sj", "47", 1], ["Swaziland", "sz", "268"], ["Sweden (Sverige)", "se", "46"], ["Switzerland (Schweiz)", "ch", "41"], ["Syria (‫سوريا‬‎)", "sy", "963"], ["Taiwan (台灣)", "tw", "886"], ["Tajikistan", "tj", "992"], ["Tanzania", "tz", "255"], ["Thailand (ไทย)", "th", "66"], ["Timor-Leste", "tl", "670"], ["Togo", "tg", "228"], ["Tokelau", "tk", "690"], ["Tonga", "to", "676"], ["Trinidad and Tobago", "tt", "1868"], ["Tunisia (‫تونس‬‎)", "tn", "216"], ["Turkey (Türkiye)", "tr", "90"], ["Turkmenistan", "tm", "993"], ["Turks and Caicos Islands", "tc", "1649"], ["Tuvalu", "tv", "688"], ["U.S. Virgin Islands", "vi", "1340"], ["Uganda", "ug", "256"], ["Ukraine (Україна)", "ua", "380"], ["United Arab Emirates (‫الإمارات العربية المتحدة‬‎)", "ae", "971"], ["United Kingdom", "gb", "44", 0], ["United States", "us", "1", 0], ["Uruguay", "uy", "598"], ["Uzbekistan (Oʻzbekiston)", "uz", "998"], ["Vanuatu", "vu", "678"], ["Vatican City (Città del Vaticano)", "va", "39", 1], ["Venezuela", "ve", "58"], ["Vietnam (Việt Nam)", "vn", "84"], ["Wallis and Futuna", "wf", "681"], ["Western Sahara (‫الصحراء الغربية‬‎)", "eh", "212", 1], ["Yemen (‫اليمن‬‎)", "ye", "967"], ["Zambia", "zm", "260"], ["Zimbabwe", "zw", "263"], ["Åland Islands", "ax", "358", 1]], b = 0; b < v.length; b++) {
        var y = v[b];
        v[b] = {name: y[0], iso2: y[1], dialCode: y[2], priority: y[3] || 0, areaCodes: y[4] || null}
    }
}), function (t, e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? module.exports = e(require("jquery")) : t.lightbox = e(t.jQuery)
}(this, function (d) {
    function t(t) {
        this.album = [], this.currentImageIndex = void 0, this.init(), this.options = d.extend({}, this.constructor.defaults), this.option(t)
    }

    return t.defaults = {
        albumLabel: "Image %1 of %2",
        alwaysShowNavOnTouchDevices: !1,
        fadeDuration: 600,
        fitImagesInViewport: !0,
        imageFadeDuration: 600,
        positionFromTop: 50,
        resizeDuration: 700,
        showImageNumberLabel: !0,
        wrapAround: !1,
        disableScrolling: !1,
        sanitizeTitle: !1
    }, t.prototype.option = function (t) {
        d.extend(this.options, t)
    }, t.prototype.imageCountLabel = function (t, e) {
        return this.options.albumLabel.replace(/%1/g, t).replace(/%2/g, e)
    }, t.prototype.init = function () {
        var t = this;
        d(document).ready(function () {
            t.enable(), t.build()
        })
    }, t.prototype.enable = function () {
        var e = this;
        d("body").on("click", "a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]", function (t) {
            return e.start(d(t.currentTarget)), !1
        })
    }, t.prototype.build = function () {
        var e;
        0 < d("#lightbox").length || (e = this, d('<div id="lightboxOverlay" tabindex="-1" class="lightboxOverlay"></div><div id="lightbox" tabindex="-1" class="lightbox"><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" alt=""/><div class="lb-nav"><a class="lb-prev" aria-label="Previous image" href="" ></a><a class="lb-next" aria-label="Next image" href="" ></a></div><div class="lb-loader"><a class="lb-cancel"></a></div></div></div><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"></a></div></div></div></div>').appendTo(d("body")), this.$lightbox = d("#lightbox"), this.$overlay = d("#lightboxOverlay"), this.$outerContainer = this.$lightbox.find(".lb-outerContainer"), this.$container = this.$lightbox.find(".lb-container"), this.$image = this.$lightbox.find(".lb-image"), this.$nav = this.$lightbox.find(".lb-nav"), this.containerPadding = {
            top: parseInt(this.$container.css("padding-top"), 10),
            right: parseInt(this.$container.css("padding-right"), 10),
            bottom: parseInt(this.$container.css("padding-bottom"), 10),
            left: parseInt(this.$container.css("padding-left"), 10)
        }, this.imageBorderWidth = {
            top: parseInt(this.$image.css("border-top-width"), 10),
            right: parseInt(this.$image.css("border-right-width"), 10),
            bottom: parseInt(this.$image.css("border-bottom-width"), 10),
            left: parseInt(this.$image.css("border-left-width"), 10)
        }, this.$overlay.hide().on("click", function () {
            return e.end(), !1
        }), this.$lightbox.hide().on("click", function (t) {
            "lightbox" === d(t.target).attr("id") && e.end()
        }), this.$outerContainer.on("click", function (t) {
            return "lightbox" === d(t.target).attr("id") && e.end(), !1
        }), this.$lightbox.find(".lb-prev").on("click", function () {
            return 0 === e.currentImageIndex ? e.changeImage(e.album.length - 1) : e.changeImage(e.currentImageIndex - 1), !1
        }), this.$lightbox.find(".lb-next").on("click", function () {
            return e.currentImageIndex === e.album.length - 1 ? e.changeImage(0) : e.changeImage(e.currentImageIndex + 1), !1
        }), this.$nav.on("mousedown", function (t) {
            3 === t.which && (e.$nav.css("pointer-events", "none"), e.$lightbox.one("contextmenu", function () {
                setTimeout(function () {
                    this.$nav.css("pointer-events", "auto")
                }.bind(e), 0)
            }))
        }), this.$lightbox.find(".lb-loader, .lb-close").on("click", function () {
            return e.end(), !1
        }))
    }, t.prototype.start = function (t) {
        var e = this, i = d(window);
        i.on("resize", d.proxy(this.sizeOverlay, this)), this.sizeOverlay(), this.album = [];
        var n = 0;

        function o(t) {
            e.album.push({
                alt: t.attr("data-alt"),
                link: t.attr("href"),
                title: t.attr("data-title") || t.attr("title")
            })
        }

        var s = t.attr("data-lightbox");
        if (s) for (var a = d(t.prop("tagName") + '[data-lightbox="' + s + '"]'), r = 0; r < a.length; r = ++r) o(d(a[r])), a[r] === t[0] && (n = r); else if ("lightbox" === t.attr("rel")) o(t); else {
            a = d(t.prop("tagName") + '[rel="' + t.attr("rel") + '"]');
            for (var l = 0; l < a.length; l = ++l) o(d(a[l])), a[l] === t[0] && (n = l)
        }
        s = i.scrollTop() + this.options.positionFromTop, i = i.scrollLeft();
        this.$lightbox.css({
            top: s + "px",
            left: i + "px"
        }).fadeIn(this.options.fadeDuration), this.options.disableScrolling && d("body").addClass("lb-disable-scrolling"), this.changeImage(n)
    }, t.prototype.changeImage = function (o) {
        var s = this, a = this.album[o].link, r = a.split(".").slice(-1)[0], l = this.$lightbox.find(".lb-image");
        this.disableKeyboardNav(), this.$overlay.fadeIn(this.options.fadeDuration), d(".lb-loader").fadeIn("slow"), this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide(), this.$outerContainer.addClass("animating");
        var c = new Image;
        c.onload = function () {
            var t, e, i, n;
            l.attr({
                alt: s.album[o].alt,
                src: a
            }), d(c), l.width(c.width), l.height(c.height), n = d(window).width(), i = d(window).height(), n = n - s.containerPadding.left - s.containerPadding.right - s.imageBorderWidth.left - s.imageBorderWidth.right - 20, i = i - s.containerPadding.top - s.containerPadding.bottom - s.imageBorderWidth.top - s.imageBorderWidth.bottom - s.options.positionFromTop - 70, "svg" === r && (l.width(n), l.height(i)), s.options.fitImagesInViewport ? (s.options.maxWidth && s.options.maxWidth < n && (n = s.options.maxWidth), s.options.maxHeight && s.options.maxHeight < i && (i = s.options.maxHeight)) : (n = s.options.maxWidth || c.width || n, i = s.options.maxHeight || c.height || i), (c.width > n || c.height > i) && (c.width / n > c.height / i ? (e = n, t = parseInt(c.height / (c.width / e), 10)) : (t = i, e = parseInt(c.width / (c.height / t), 10)), l.width(e), l.height(t)), s.sizeContainer(l.width(), l.height())
        }, c.src = this.album[o].link, this.currentImageIndex = o
    }, t.prototype.sizeOverlay = function () {
        var t = this;
        setTimeout(function () {
            t.$overlay.width(d(document).width()).height(d(document).height())
        }, 0)
    }, t.prototype.sizeContainer = function (t, e) {
        var i = this, n = this.$outerContainer.outerWidth(), o = this.$outerContainer.outerHeight(),
            s = t + this.containerPadding.left + this.containerPadding.right + this.imageBorderWidth.left + this.imageBorderWidth.right,
            a = e + this.containerPadding.top + this.containerPadding.bottom + this.imageBorderWidth.top + this.imageBorderWidth.bottom;

        function r() {
            i.$lightbox.find(".lb-dataContainer").width(s), i.$lightbox.find(".lb-prevLink").height(a), i.$lightbox.find(".lb-nextLink").height(a), i.$overlay.focus(), i.showImage()
        }

        n !== s || o !== a ? this.$outerContainer.animate({
            width: s,
            height: a
        }, this.options.resizeDuration, "swing", function () {
            r()
        }) : r()
    }, t.prototype.showImage = function () {
        this.$lightbox.find(".lb-loader").stop(!0).hide(), this.$lightbox.find(".lb-image").fadeIn(this.options.imageFadeDuration), this.updateNav(), this.updateDetails(), this.preloadNeighboringImages(), this.enableKeyboardNav()
    }, t.prototype.updateNav = function () {
        var t = !1;
        try {
            document.createEvent("TouchEvent"), t = !!this.options.alwaysShowNavOnTouchDevices
        } catch (t) {
        }
        this.$lightbox.find(".lb-nav").show(), 1 < this.album.length && (this.options.wrapAround ? (t && this.$lightbox.find(".lb-prev, .lb-next").css("opacity", "1"), this.$lightbox.find(".lb-prev, .lb-next").show()) : (0 < this.currentImageIndex && (this.$lightbox.find(".lb-prev").show(), t && this.$lightbox.find(".lb-prev").css("opacity", "1")), this.currentImageIndex < this.album.length - 1 && (this.$lightbox.find(".lb-next").show(), t && this.$lightbox.find(".lb-next").css("opacity", "1"))))
    }, t.prototype.updateDetails = function () {
        var t, e = this;
        void 0 !== this.album[this.currentImageIndex].title && "" !== this.album[this.currentImageIndex].title && (t = this.$lightbox.find(".lb-caption"), this.options.sanitizeTitle ? t.text(this.album[this.currentImageIndex].title) : t.html(this.album[this.currentImageIndex].title), t.fadeIn("fast")), 1 < this.album.length && this.options.showImageNumberLabel ? (t = this.imageCountLabel(this.currentImageIndex + 1, this.album.length), this.$lightbox.find(".lb-number").text(t).fadeIn("fast")) : this.$lightbox.find(".lb-number").hide(), this.$outerContainer.removeClass("animating"), this.$lightbox.find(".lb-dataContainer").fadeIn(this.options.resizeDuration, function () {
            return e.sizeOverlay()
        })
    }, t.prototype.preloadNeighboringImages = function () {
        this.album.length > this.currentImageIndex + 1 && ((new Image).src = this.album[this.currentImageIndex + 1].link), 0 < this.currentImageIndex && ((new Image).src = this.album[this.currentImageIndex - 1].link)
    }, t.prototype.enableKeyboardNav = function () {
        this.$lightbox.on("keyup.keyboard", d.proxy(this.keyboardAction, this)), this.$overlay.on("keyup.keyboard", d.proxy(this.keyboardAction, this))
    }, t.prototype.disableKeyboardNav = function () {
        this.$lightbox.off(".keyboard"), this.$overlay.off(".keyboard")
    }, t.prototype.keyboardAction = function (t) {
        var e = t.keyCode;
        27 === e ? (t.stopPropagation(), this.end()) : 37 === e ? 0 !== this.currentImageIndex ? this.changeImage(this.currentImageIndex - 1) : this.options.wrapAround && 1 < this.album.length && this.changeImage(this.album.length - 1) : 39 === e && (this.currentImageIndex !== this.album.length - 1 ? this.changeImage(this.currentImageIndex + 1) : this.options.wrapAround && 1 < this.album.length && this.changeImage(0))
    }, t.prototype.end = function () {
        this.disableKeyboardNav(), d(window).off("resize", this.sizeOverlay), this.$lightbox.fadeOut(this.options.fadeDuration), this.$overlay.fadeOut(this.options.fadeDuration), this.options.disableScrolling && d("body").removeClass("lb-disable-scrolling")
    }, new t
});
var quickViewgalleryThumbs, mobile_image_swiper, quickViewgalleryTop, custom_url = location.href,
    is_rtl = $("#body").data("is-rtl"), mode = 1 == is_rtl ? "right" : "left";
const is_loggedin = $("#is_loggedin").val(),
    Toast = Swal.mixin({toast: !0, position: "top-end", showConfirmButton: !1, timer: 3e3, timerProgressBar: !0});

function queryParams(t) {
    return {limit: t.limit, sort: t.sort, order: t.order, offset: t.offset, search: t.search}
}

function onSignInSubmit(t) {
    var e;
    t.preventDefault(), isPhoneNumberValid() && ($("#send-otp-button").html("Please Wait..."), e = is_user_exist(), updateSignInButtonUI(), 1 == e.error ? ($("#is-user-exist-error").html(e.message), $("#send-otp-button").html("Send OTP")) : (window.signingIn = !0, t = getPhoneNumberFromUserInput(), e = window.recaptchaVerifier, firebase.auth().signInWithPhoneNumber(t, e).then(function (n) {
        $("#send-otp-button").html("Send OTP"), $(".send-otp-form").unblock(), window.signingIn = !1, updateSignInButtonUI(), resetRecaptcha(), $("#send-otp-form").hide(), $("#otp_div").show(), $("#verify-otp-form").removeClass("d-none"), $(document).on("submit", "#verify-otp-form", function (t) {
            t.preventDefault(), $("#registration-error").html("");
            var t = $("#otp").val(), e = new FormData(this), i = $(this).attr("action");
            $("#register_submit_btn").html("Please Wait...").attr("disabled", !0), n.confirm(t).then(function (t) {
                e.append(csrfName, csrfHash), e.append("mobile", $("#phone-number").val()), e.append("country_code", $(".selected-dial-code").text()), $.ajax({
                    type: "POST",
                    url: i,
                    data: e,
                    processData: !1,
                    contentType: !1,
                    cache: !1,
                    dataType: "json",
                    beforeSend: function () {
                        $("#register_submit_btn").html("Please Wait...").attr("disabled", !0)
                    },
                    success: function (t) {
                        csrfName = t.csrfName, csrfHash = t.csrfHash, $("#register_submit_btn").html("Submit").attr("disabled", !1), $("#registration-error").html(t.message).show()
                    }
                })
            }).catch(function (t) {
                $("#register_submit_btn").html("Please Wait...").attr("disabled", !0), $("#registration-error").html("Invalid OTP. Please Enter Valid OTP").show()
            })
        })
    }).catch(function (t) {
        window.signingIn = !1, $("#is-user-exist-error").html(t.message).show(), $("#send-otp-button").html("Send OTP"), updateSignInButtonUI(), resetRecaptcha()
    })))
}

function getPhoneNumberFromUserInput() {
    return $(".selected-dial-code").html() + $("#phone-number").val()
}

function isPhoneNumberValid() {
    return -1 !== getPhoneNumberFromUserInput().search(/^\+[0-9\s\-\(\)]+$/)
}

function resetRecaptcha() {
    return window.recaptchaVerifier.render().then(function (t) {
        grecaptcha.reset(t)
    })
}

function updateSignInButtonUI() {
}

function is_user_exist(t = "") {
    var e;
    return t = "" == t ? $("#phone-number").val() : t, $.ajax({
        type: "POST",
        async: !1,
        url: base_url + "auth/verify_user",
        data: {mobile: t, [csrfName]: csrfHash},
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, e = t
        }
    }), e
}

function formatRepo(t) {
    if (t.loading) return t.text;
    var e = "<div class='select2-result-repository clearfix'><div class='select2-result-repository__avatar'><img src='" + t.image_sm + "' /></div><div class='select2-result-repository__meta'><div class='select2-result-repository__title'>" + t.name + "</div>";
    return t.category_name && (e += "<div class='select2-result-repository__description'> In " + t.category_name + "</div>"), e
}

function formatRepoSelection(t) {
    return t.name || t.text
}

$(document).on("submit", ".form-submit-event", function (t) {
    t.preventDefault();
    var e = new FormData(this), i = $(this).attr("id"), n = $("#error_box", this), o = $(this).find(".submit_btn"),
        s = $(this).find(".submit_btn").html(), t = $(this).find(".submit_btn").val(),
        a = "" != s || "undefined" != s ? s : t;
    e.append(csrfName, csrfHash), $.ajax({
        type: "POST", url: $(this).attr("action"), data: e, beforeSend: function () {
            o.html("Please Wait.."), o.attr("disabled", !0)
        }, cache: !1, contentType: !1, processData: !1, dataType: "json", success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 1 == t.error ? (n.addClass("rounded p-3 alert alert-danger").removeClass("d-none alert-success"), n.show().delay(5e3).fadeOut(), n.html(t.message), o.html(a), o.attr("disabled", !1)) : (n.addClass("rounded p-3 alert alert-success").removeClass("d-none alert-danger"), n.show().delay(3e3).fadeOut(), n.html(t.message), o.html(a), o.attr("disabled", !1), $(".form-submit-event")[0].reset(), "login_form" == i && cart_sync(), setTimeout(function () {
                location.reload()
            }, 600))
        }
    })
}), window.onload = function () {
    document.getElementById("send-otp-form").addEventListener("submit", onSignInSubmit), document.getElementById("phone-number").addEventListener("keyup", updateSignInButtonUI), document.getElementById("phone-number").addEventListener("change", updateSignInButtonUI)
}, $(document).on("click", "#resend-otp", function (t) {
    t.preventDefault()
}), $(document).on("submit", ".sign-up-form", function (t) {
    t.preventDefault();
    t = $(".selected-dial-code").html();
    $phonenumber = $("#phone-number").val(), $username = $('input[name="username"]').val(), $email = $('input[name="email"]').val(), $passwd = $('input[name="password"]').val(), $.ajax({
        type: "POST",
        url: base_url + "auth/register_user",
        data: {
            country_code: t,
            mobile: $phonenumber,
            name: $username,
            email: $email,
            password: $passwd,
            [csrfName]: csrfHash
        },
        dataType: "json",
        success: function (t) {
            1 == t.error && $("#sign-up-error").html('<span class="text-danger" >' + response.message + "</span>")
        }
    })
});
var search_products = $(".search_product").select2({
    ajax: {
        url: base_url + "home/get_products",
        dataType: "json",
        delay: 250,
        data: function (t) {
            return {search: t.term, page: t.page}
        },
        processResults: function (t, e) {
            return e.page = e.page || 1, {results: t.data, pagination: {more: 30 * e.page < t.total}}
        },
        cache: !0
    },
    escapeMarkup: function (t) {
        return t
    },
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    theme: "adwitt",
    placeholder: "Search for products"
});
search_products.on("select2:select", function (t) {
    t = t.params.data;
    null != t.link && null != t.link && (window.location.href = t.link)
}), $("#leftside-navigation .sub-menu > a").click(function (t) {
    $("#leftside-navigation ul ul").slideUp(), $("#leftside-navigation .sub-menu > a").next().is(":visible") || $("#leftside-navigation .sub-menu > a").find(".arrow").removeClass("fa-angle-down").addClass("fa-angle-left"), $(this).find(".arrow").hasClass("fa-angle-left") ? $(this).find(".arrow").removeClass("fa-angle-left").addClass("fa-angle-down") : $(this).find(".arrow").removeClass("fa-angle-down").addClass("fa-angle-left"), $(this).next().is(":visible") || $(this).next().slideDown(), t.stopPropagation()
}), $("li.has-ul").click(function () {
    $(this).children(".sub-ul").slideToggle(500), $(this).toggleClass("active"), event.preventDefault()
}), $(".add-to-fav-btn").on("click", function (t) {
    t.preventDefault();
    var e = new FormData, t = $(this).data("product-id"), i = $(this);
    e.append(csrfName, csrfHash), e.append("product_id", t), $.ajax({
        type: "POST",
        url: base_url + "my-account/manage-favorites",
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 1 == t.error ? Toast.fire({
                icon: "error",
                title: t.message
            }) : i.hasClass("far") ? i.removeClass("far").addClass("fa text-danger") : (i.removeClass("fa text-danger").addClass("far"), i.css("color", "#adadad"))
        }
    })
}), $(document).on("click", "#add_to_favorite_btn", function (t) {
    t.preventDefault();
    var e = new FormData, t = $(this).data("product-id"), i = $(this), n = $(this).html();
    e.append(csrfName, csrfHash), e.append("product_id", t), $.ajax({
        type: "POST",
        url: base_url + "my-account/manage-favorites",
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        beforeSend: function () {
            i.attr("disabled", !0), i.find("span").text("Please wait")
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, i.attr("disabled", !1), i.html(n), 1 == t.error ? Toast.fire({
                icon: "error",
                title: t.message
            }) : i.hasClass("add-fav") ? (i.removeClass("add-fav").addClass("remove-fav"), i.find("span").text("Remove from Favorite")) : (i.removeClass("remove-fav").addClass("add-fav"), i.find("span").text("Add to Favorite"))
        }
    })
}), $(function () {
    var t, o, l;

    function s(t, s, a, r = "#user_image_data") {
        $("#review-image-title").data("review-offset", a + s), $.getJSON(base_url + "products/get_rating?product_id=" + t + "&has_images=1&limit=" + s + "&offset=" + a, function (t) {
            $("#review-image-title").data("review-offset", a + s), l = "";
            if (0 == t.error) for (var e = 0; e < t.data.product_rating.length; e++) for (var i = t.data.product_rating[e], n = 0; n < i.images.length; n++) {
                var o = i.images;
                l += "<div class='review-box '><a href='" + o[n] + "' data-lightbox='review-images-12345' data-title='<font >" + i.rating + " &#9733;</font></br>" + i.user_name + "<br>" + i.comment + "'><img src='" + o[n] + "' alt='Review Image'></a></div>"
            } else $("#review-image-title").data("reached-end", "true");
            $(r).append(l)
        })
    }

    $(".auth-modal").iziModal({
        overlayClose: !1,
        overlayColor: "rgba(0, 0, 0, 0.6)"
    }), $("#user-review-images").length && (t = "", t = $("#review-image-title").data("review-title"), o = $("#review-image-title").data("product-id"), l = "", $("#user-review-images").iziModal({
        overlayClose: !1,
        overlayColor: "rgba(0, 0, 0, 0.6)",
        title: t,
        headerColor: "#f44336c4",
        arrowKeys: !1,
        fullscreen: !0,
        onOpening: function (t) {
            t.startLoading();
            var e = $("#review-image-title").data("review-limit"), i = $("#review-image-title").data("review-offset"),
                n = $("#review-image-title").data("reached-end");
            $("#load_more_div").html('<div id="load_more"></div>'), 0 == n && s(o, e, i), t.stopLoading()
        },
        onOpened: function () {
            $("div").bind("wheel", function (t) {
                var e, i, n;
                $("#load_more").length && $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight && (e = $("#review-image-title").data("product-id"), i = $("#review-image-title").data("review-limit"), n = $("#review-image-title").data("review-offset"), 0 == $("#review-image-title").data("reached-end") && s(e, i, n))
            })
        }
    })), $("#seller_info").length && $("#seller_info").iziModal({
        overlayClose: !0,
        overlayColor: "rgba(0, 0, 0, 0.6)",
        title: "Sold By",
        headerColor: "#f44336c4",
        arrowKeys: !1,
        fullscreen: !0,
        onOpening: function (t) {
            t.startLoading(), t.stopLoading()
        }
    }), $("#quick-view").iziModal({
        overlayClose: !1, overlayColor: "rgba(0, 0, 0, 0.6)", width: 1e3, onOpening: function (d) {
            d.startLoading(), $("#modal-product-tags").html(""), $.getJSON(base_url + "products/get-details/" + d.$element.data("dataProductId"), function (i) {
                var n = 0;
                $("#modal-add-to-cart-button").attr("data-product-id", i.id), "simple_product" == i.type || "digital_product" == i.type ? $("#modal-add-to-cart-button").attr("data-product-variant-id", i.variants[0].id) : $("#modal-add-to-cart-button").attr("data-product-variant-id", ""), 1 != i.minimum_order_quantity && "" != i.minimum_order_quantity && "undefined" != i.minimum_order_quantity ? ($(".in-num").attr({"data-min": i.minimum_order_quantity}), $(".minus").attr({"data-min": i.minimum_order_quantity}), $("#modal-add-to-cart-button").attr({"data-min": i.minimum_order_quantity})) : ($(".in-num").attr({"data-min": 1}), $(".minus").attr({"data-min": 1}), $("#modal-add-to-cart-button").attr({"data-min": 1})), 1 != i.quantity_step_size && "" != i.quantity_step_size && "undefined" != i.quantity_step_size ? ($(".in-num").attr({"data-step": i.quantity_step_size}), $(".minus").attr({"data-step": i.quantity_step_size}), $(".plus").attr({"data-step": i.quantity_step_size}), $("#modal-add-to-cart-button").attr({"data-step": i.quantity_step_size})) : ($(".in-num").attr({"data-step": 1}), $(".minus").attr({"data-step": 1}), $(".plus").attr({"data-step": 1}), $("#modal-add-to-cart-button").attr({"data-step": 1})), "" != i.total_allowed_quantity && "undefined" != i.total_allowed_quantity && null != i.total_allowed_quantity ? ($(".in-num").attr({"data-max": i.total_allowed_quantity}), $(".plus").attr({"data-max": i.total_allowed_quantity}), $("#modal-add-to-cart-button").attr({"data-max": i.total_allowed_quantity})) : ($(".in-num").attr({"data-max": 1}), $(".plus").attr({"data-max": 1}), $("#modal-add-to-cart-button").attr({"data-max": 1})), $("#modal-product-quantity").val(i.minimum_order_quantity), $("#modal-product-title").text(i.name), $("#modal-product-short-description").text(i.short_description), $("#modal-product-rating").rating("update", i.rating);
                var t = i.get_price.range;
                $("#modal-product-price").html(t), quickViewgalleryThumbs = new Swiper(".gallery-thumbs", {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: !0,
                    watchSlidesVisibility: !0,
                    watchSlidesProgress: !0
                }), quickViewgalleryTop = new Swiper(".gallery-top", {
                    spaceBetween: 10,
                    navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"},
                    thumbs: {swiper: quickViewgalleryThumbs},
                    clickable: !0
                }), mobile_image_swiper = new Swiper(".mobile-image-swiper", {
                    pagination: {el: ".mobile-image-swiper-pagination"},
                    clickable: !0
                }), quickViewgalleryThumbs.removeAllSlides(), quickViewgalleryTop.removeAllSlides(), mobile_image_swiper.removeAllSlides();
                var o = '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + i.image_md + '" data-zoom-image=""></div></div></div></div>',
                    s = '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + i.image_md + '"></div></div></div></div>',
                    a = '<div class="swiper-slide text-center"><img src="' + i.image_md + '"></div>',
                    e = i.variants.map(function (t, e) {
                        return t.images_md
                    });
                $.each(e, function (t, e) {
                    null != e && "" != e && $.each(e, function (t, e) {
                        o += '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + e + '" data-zoom-image=""></div></div></div></div>', s += '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + e + '"></div></div></div></div>', a += '<div class="swiper-slide text-center"><img src="' + e + '"></div>'
                    })
                }), $.each(i.other_images_md, function (t, e) {
                    n++, o += '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + e + '" data-zoom-image=""></div></div></div></div>', s += '<div class="swiper-slide text-center"><div class="product-view-grid"><div class="product-view-image"><div class="product-view-image-container"><img src="' + e + '"></div></div></div></div>', a += '<div class="swiper-slide text-center"><img src="' + e + '"></div>'
                }), quickViewgalleryThumbs.addSlide(1, o), quickViewgalleryTop.addSlide(1, s), mobile_image_swiper.addSlide(1, a);
                var r = "";
                $.each(i.variant_attributes, function (t, n) {
                    var e = n.ids.split(","), o = n.values.split(","), s = n.swatche_type.split(","),
                        a = n.swatche_value.split(",");
                    r += "<h4>" + n.attr_name + '</h4><div class="btn-group btn-group-toggle" data-toggle="buttons">', $.each(e, function (t, e) {
                        var i;
                        "1" == s[t] ? (i = 'style="background-color:' + a[t] + '";', r += '<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style><button class="btn fullCircle" ' + i + '><input type="radio" name="' + n.attr_name + '" value="' + e + '" class="modal-product-attributes" autocomplete="off"><br></button>') : "2" == s[t] ? r += '<style> .product-page-details .btn-group>.active { color: #000000; border: 1px solid black;}</style><label class="btn text-center bg-transparent"><img class="swatche-image" src="' + a[t] + '"><input type="radio" name="' + n.attr_name + '" value="' + e + '" class="modal-product-attributes" autocomplete="off"><br></label>' : r += '<style> .product-page-details .btn-group>.active { background-color: var(--primary-color);color: white!important;}</style><label class="btn btn-default text-center"><input type="radio" name="' + n.attr_name + '" value="' + e + '" class="modal-product-attributes" autocomplete="off">' + o[t] + "<br></label>"
                    }), r += "</div>"
                });
                var t = 0 == i.is_deliverable ? "danger" : "success", e = 0 == i.is_deliverable ? "not" : "",
                    e = "" != i.zipcode && void 0 !== i.zipcode ? '<b class="text-' + t + '">Product is ' + e + " delivarable on &quot; " + i.zipcode + " &quot; </b>" : "";
                "digital_product" != i.type ? r += '<form class="mt-2 validate_zipcode_quick_view "   method="post" ><div class="d-flex"><div class=" col-md-6 pl-0"><input type="hidden" name="product_id" value="' + i.id + '"><input type="hidden" name="' + csrfName + '" value="' + csrfHash + '"><input type="text" class="form-control" id="zipcode" placeholder="Zipcode" name="zipcode" required value="' + i.zipcode + '"></div><button type="submit" class="button button-primary-outline m-0" data-product_id="' + i.id + '"  data-zipcode="' + i.zipcode + '"  id="validate_zipcode">Check Availability</button></div><div class="mt-2" id="error_box1">' + e + " </div> </form>" : r += '<form class="mt-2 validate_zipcode_quick_view "   method="post" ><div class="d-flex"><div class=" col-md-6 pl-0"><input type="hidden" name="product_id" value="' + i.id + '"><input type="hidden" name="' + csrfName + '" value="' + csrfHash + '"></div></div><div class="mt-2" id="error_box1">' + e + " </div> </form>", $("#modal-product-variant-attributes").html(r), 0 == i.is_deliverable && "" != i.zipcode && void 0 !== i.zipcode ? $("#modal-add-to-cart-button").attr("disabled", "true") : $("#modal-add-to-cart-button").removeAttr("disabled");
                var l = "", n = 1;
                $.each(i.variants, function (t, e) {
                    l += '<input type="hidden" class="modal-product-variants" data-image-index="' + n + '" name="variants_ids" data-name="' + i.name + '" value="' + e.variant_ids + '" data-id="' + e.id + '" data-price="' + e.price + '" data-special_price="' + e.special_price + '">', n += e.images.length
                }), $("#modal-product-variants-div").html(l), $("#add_to_favorite_btn").attr("data-product-id", i.id), 1 == i.is_favorite ? ($("#add_to_favorite_btn").addClass("remove-fav"), $("#add_to_favorite_btn").find("span").text("Remove From Favorite")) : ($("#add_to_favorite_btn").addClass("add-fav"), $("#add_to_favorite_btn").find("span").text("Add to Favorite")), $("#compare").attr("data-product-id", i.id), "simple_product" == i.type ? $("#compare").attr("data-product-variant-id", i.variants[0].id) : $("#compare").attr("data-product-variant-id", "");
                var c;
                $.each(i, function (t, e) {
                    i.id, i.variants.id
                }), $("#modal-product-no-of-ratings").text(i.no_of_ratings), $.isEmptyObject(i.tags) || (c = "Tags ", $.each(i.tags, function (t, e) {
                    c += '<a href="' + base_url + "products/tags/" + e + '" target="_blank"><span class="badge badge-secondary p-1 mr-1">' + e + "</span></a>"
                }), $("#modal-product-tags").html(c));
                e = "";
                i.seller_name && (e = '<p> <span class="text-secondary"> Sold by </span> <a class="text text-primary" target="_blank" href="' + base_url + "products?seller=" + i.seller_slug + '">' + i.seller_name + '</a> <span class="badge badge-success ">' + i.seller_rating + ' <i class="fa fa-star"></i></span> <small class="text-muted"> Out of</small> <b> ' + i.seller_no_of_ratings + " </b></p>", $("#modal-product-sellers").html(e)), d.stopLoading()
            })
        }
    }), $(document).on("change", ".modal-product-attributes", function (t) {
        t.preventDefault();
        var e, n, o = [], s = "", a = !1, i = [], r = [], l = [], c = [], d = [], u = [];
        $(".modal-product-variants").each(function () {
            r = {
                price: $(this).data("price"),
                special_price: $(this).data("special_price")
            }, d.push($(this).data("id")), l.push(r), i = $(this).val().split(","), c.push(i), u.push($(this).data("image-index"))
        }), e = i.length, $(".modal-product-attributes").each(function () {
            var i;
            $(this).prop("checked") && (o.push($(this).val()), o.length == e && (r = [], i = "", $.each(c, function (t, e) {
                arrays_equal(o, e) && (a = !0, r.push(l[t]), i = d[t], n = u[t])
            }), a ? (quickViewgalleryTop.slideTo(n, 500, !1), mobile_image_swiper.slideTo(n, 500, !1), r[0].special_price < r[0].price && 0 != r[0].special_price ? (s = r[0].special_price, $("#modal-product-price").text(currency + " " + s), $("#modal-product-special-price").text(currency + " " + r[0].price), $("#modal-add-to-cart-button").attr("data-product-variant-id", i), $("#modal-product-special-price-div").show()) : (s = r[0].price, $("#modal-product-price").html(currency + " " + s), $("#modal-product-special-price-div").hide(), $("#modal-add-to-cart-button").attr("data-product-variant-id", i))) : $("#modal-product-special-price-div").hide()))
        })
    }), $("#modal-add-to-cart-button").on("click", function (t) {
        t.preventDefault();
        var n = $("#modal-product-quantity").val(), s = $("#modal-product-title").text(),
            a = $("#modal-product-short-description").text(), r = $(".product-view-image-container img").attr("src"),
            l = $("#modal-product-price").text().replace(/\D/g, "");
        $("#quick-view").data("data-product-id", $(this).data("productId"));
        var c = $(this).attr("data-product-variant-id"), d = $(this).attr("data-min"), u = $(this).attr("data-max"),
            h = $(this).attr("data-step"), p = $(this), f = $(this).html();
        c ? $.ajax({
            type: "POST",
            url: base_url + "cart/manage",
            data: {
                product_variant_id: c,
                qty: $("#modal-product-quantity").val(),
                is_saved_for_later: !1,
                [csrfName]: csrfHash
            },
            dataType: "json",
            beforeSend: function () {
                p.html("Please Wait").text("Please Wait").attr("disabled", !0)
            },
            success: function (t) {
                if (csrfName = t.csrfName, csrfHash = t.csrfHash, p.html(f).attr("disabled", !1), 0 == t.error) {
                    Toast.fire({icon: "success", title: t.message}), $("#cart-count").text(t.data.cart_count);
                    var o = "";
                    $.each(t.data.items, function (t, e) {
                        var i = void 0 !== e.product_variants.variant_values && null != e.product_variants.variant_values ? e.product_variants.variant_values : "",
                            n = e.special_price < e.price && 0 != e.special_price ? e.special_price : e.price;
                        o += '<div class="row"><div class="cart-product product-sm col-md-12"><div class="product-image"><img class="pic-1" src="' + base_url + e.image + '" alt="Not Found"></div><div class="product-details"><div class="product-title">' + e.name + "</div><span>" + i + '</span><p class="product-descriptions">' + e.short_description + '</p></div><div class="product-pricing d-flex py-2 px-1 w-100"><div class="product-price align-self-center">' + currency + " " + n + '</div><div class="product-sm-quantity px-1"><input type="number" class="form-input" value="' + d + '"  data-id="' + e.product_variant_id + '" data-price="' + e.price + '" min="' + d + '" max="' + u + '" step="' + h + '" ></div><div class="product-sm-removal align-self-center"><button class="remove-product button button-danger" data-id="' + e.product_variant_id + '"><i class="fa fa-trash"></i></button></div><div class="product-line-price align-self-center px-1">' + currency + " " + (e.qty * n).toLocaleString(void 0, {minimumFractionDigits: 2}) + "</div></div></div></div>"
                    }), $("#cart-item-sidebar").html(o)
                } else {
                    if (0 == is_loggedin) {
                        Toast.fire({icon: "success", title: "Item added to cart"});
                        var e = {
                            product_variant_id: c.trim(),
                            title: s,
                            description: a,
                            qty: n,
                            image: r,
                            price: l.trim(),
                            min: d,
                            step: h
                        }, i = localStorage.getItem("cart");
                        return console.log(i), null != (i = null !== localStorage.getItem("cart") ? JSON.parse(i) : null) ? i.push(e) : i = [e], localStorage.setItem("cart", JSON.stringify(i)), void display_cart(i)
                    }
                    Toast.fire({icon: "error", title: t.message})
                }
            }
        }) : Toast.fire({icon: "error", title: "Please select variant"})
    }), $(".auth-modal").on("click", "header a", function (t) {
        t.preventDefault(), window.signingIn = !0;
        t = $(this).index();
        $(this).addClass("active").siblings("a").removeClass("active"), $(this).parents("div").find("section").eq(t).removeClass("hide").siblings("section").addClass("hide"), 0 === $(this).index() ? $(".auth-modal .iziModal-content .icon-close").css("background", "#ddd") : $(".auth-modal .iziModal-content .icon-close").attr("style", "")
    }), $(document).on("opening", ".auth-modal", function (t) {
        $(this).removeClass("d-none"), t.preventDefault(), closeNav(), $(".send-otp-form")[0].reset(), $(".send-otp-form").show(), $(".sign-up-form")[0].reset(), $(".sign-up-form").hide(), $("#is-user-exist-error").html(""), $("#sign-up-error").html(""), $("#recaptcha-container").html(""), window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier("recaptcha-container"), window.recaptchaVerifier.render().then(function (t) {
            grecaptcha.reset(t)
        });
        var e = $("#phone-number"), i = $("#error-msg"), n = $("#valid-msg");
        e.intlTelInput({
            allowExtensions: !0,
            formatOnDisplay: !0,
            autoFormat: !0,
            autoHideDialCode: !0,
            autoPlaceholder: !0,
            defaultCountry: "in",
            ipinfoToken: "yolo",
            nationalMode: !1,
            numberType: "MOBILE",
            preferredCountries: ["in", "ae", "qa", "om", "bh", "kw", "ma"],
            preventInvalidNumbers: !0,
            separateDialCode: !0,
            initialCountry: "auto",
            geoIpLookup: function (e) {
                $.get("https://ipinfo.io", function () {
                }, "jsonp").always(function (t) {
                    t = t && t.country ? t.country : "";
                    e(t)
                })
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
        });

        function o() {
            e.removeClass("error"), i.addClass("hide"), n.addClass("hide")
        }

        e.blur(function () {
            o(), $.trim(e.val()) && (e.intlTelInput("isValidNumber") ? n.removeClass("hide") : (e.addClass("error"), i.removeClass("hide")))
        }), e.on("keyup change", o)
    }), $("#quick-view").on("click", ".submit", function (t) {
        t.preventDefault();
        var e = "wobble", i = $(this).closest(".iziModal");
        i.hasClass(e) || (i.addClass(e), setTimeout(function () {
            i.removeClass(e)
        }, 1500))
    }), $("#quick-view").on("click", "header a", function (t) {
        t.preventDefault();
        t = $(this).index();
        $(this).addClass("active").siblings("a").removeClass("active"), $(this).parents("div").find("section").eq(t).removeClass("hide").siblings("section").addClass("hide"), 0 === $(this).index() ? $("#quick-view .iziModal-content .icon-close").css("background", "#ddd") : $("#quick-view .iziModal-content .icon-close").attr("style", "")
    }), $("#quick-view").on("click", ".submit", function (t) {
        t.preventDefault();
        var e = "wobble", i = $(this).closest(".iziModal");
        i.hasClass(e) || (i.addClass(e), setTimeout(function () {
            i.removeClass(e)
        }, 1500))
    }), $("#quick-view").on("click", "header a", function (t) {
        t.preventDefault();
        t = $(this).index();
        $(this).addClass("active").siblings("a").removeClass("active"), $(this).parents("div").find("section").eq(t).removeClass("hide").siblings("section").addClass("hide"), 0 === $(this).index() ? $("#quick-view .iziModal-content .icon-close").css("background", "#ddd") : $("#quick-view .iziModal-content .icon-close").attr("style", "")
    })
}), new LazyLoad({
    threshold: 0, callback_enter: function (t) {
    }, callback_exit: function (t) {
    }, callback_cancel: function (t) {
    }, callback_loading: function (t) {
    }, callback_loaded: function (t) {
    }, callback_error: function (t) {
    }, callback_finish: function () {
    }
}), function () {
    var n, o, t = document.querySelector(".range-slider");
    t && (n = t.querySelectorAll("input[type=range]"), o = t.querySelectorAll("input[type=number]"), n.forEach(function (t) {
        t.oninput = function () {
            var t = parseFloat(n[0].value), e = parseFloat(n[1].value);
            e < t && ([t, e] = [e, t]), o[0].value = t, o[1].value = e, custom_url = setUrlParameter(location.href, "min-price", t), custom_url = setUrlParameter(custom_url, "max-price", e)
        }
    }), o.forEach(function (t) {
        t.oninput = function () {
            var t, e = parseFloat(o[0].value), i = parseFloat(o[1].value);
            i < e && (t = e, o[0].value = i, o[1].value = t), n[0].value = e, n[1].value = i
        }
    }))
}(), $(document).on("change", "input.in-num", function (t) {
    t.preventDefault();
    t = $(this);
    null != t.val() && "string" != typeof t.val() || $.isNumeric(t.val()) && "0" != t.val() || t.val(1)
}), $(document).on("focusout", ".in-num", function (t) {
    t.preventDefault();
    var e = $(this).val(), i = $(this).data("min"), t = ($(this).data("step"), $(this).data("max"));
    e < i ? ($(this).val(i), Toast.fire({
        icon: "error",
        title: "Minimum allowed quantity is " + i
    })) : t < e && ($(this).val(t), Toast.fire({icon: "error", title: "Maximum allowed quantity is " + t}))
}), $(document).on("click", ".num-block .num-in span", function (t) {
    t.preventDefault();
    var e, i, n, t = $(this).parents(".num-block").find("input.in-num");
    return null == t.val() && t.val(1), $(this).hasClass("minus") ? (e = $(this).data("step"), n = parseFloat(t.val()) - e, (i = $(this).data("min")) <= n ? t.val(n) : (t.val(i), Toast.fire({
        icon: "error",
        title: "Minimum allowed quantity is " + i
    }))) : (e = $(this).data("step"), i = $(this).data("max"), n = parseFloat(t.val()) + e, 0 != i ? n <= i ? (t.val(n), 1 < n && $(this).parents(".num-block").find(".minus").removeClass("dis")) : (t.val(i), Toast.fire({
        icon: "error",
        title: "Maximum allowed quantity is " + i
    })) : t.val(n)), t.change(), !1
}), $(document).ready(function () {
    $(".kv-fa").rating({
        theme: "krajee-fa",
        filledStar: '<i class="fas fa-star"></i>',
        emptyStar: '<i class="far fa-star"></i>',
        showClear: !1,
        showCaption: !1,
        size: "md"
    });
    var o = .05, s = 15, a = 300;

    function r() {
        var t = 0;
        $(".product").each(function () {
            t += parseFloat($(this).children(".product-line-price").text())
        });
        var e = t * o, i = 0 < t ? s : 0, n = t + e + i;
        $(".totals-value").fadeOut(a, function () {
            $("#cart-subtotal").html(t.toFixed(2)), $("#cart-tax").html(e.toFixed(2)), $("#cart-shipping").html(i.toFixed(2)), $("#cart-total").html(n.toFixed(2)), 0 == n ? $(".checkout").fadeOut(a) : $(".checkout").fadeIn(a), $(".totals-value").fadeIn(a)
        })
    }

    function l(t, e) {
        var i;
        i = "cart" == t.data("page") ? $(t).parent().parent().parent().siblings(".total-price") : $(t).parent().parent();
        var n = e * $(t).val();
        i.children(".product-line-price").each(function () {
            $(this).fadeOut(a, function () {
                $(this).text(currency + " " + n.toFixed(2)), r(), usercartTotal(), $(this).fadeIn(a)
            })
        })
    }

    function c(t) {
        var e = $(t);
        e.slideUp(a, function () {
            e.remove(), r()
        })
    }

    $(document).on("change", ".product-quantity input,.product-sm-quantity input,.itemQty", function (t) {
        t.preventDefault();
        var e = $(this).data("id"), i = $(this).data("price"), n = $(this).val(), o = $(this);
        let s;
        s = $(this).attr("step") ? $(this).attr("step") : $(this).data("step");
        t = $(this).attr("min");
        n <= 0 ? Toast.fire({
            icon: "error",
            title: `Oops! Please set minimum ${t} quantity for product`
        }) : n % s == 0 ? 1 == is_loggedin ? $.ajax({
            url: base_url + "cart/manage",
            type: "POST",
            data: {product_variant_id: e, qty: n, [csrfName]: csrfHash},
            dataType: "json",
            success: function (t) {
                csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? l(o, i) : Toast.fire({
                    icon: "error",
                    title: t.message
                })
            }
        }) : l(o, i) : Toast.fire({icon: "error", title: "Oops! you can only set quantity in step size of " + s})
    }), $(document).on("click", ".product-removal button,.product-removal i,.product-sm-removal button", function (t) {
        t.preventDefault();
        var e = $(this).data("id"),
            i = void 0 !== $(this).data("is-save-for-later") && 1 == $(this).data("is-save-for-later") ? "1" : "0",
            n = $(this).parent().parent().parent();
        confirm("Are you sure want to remove this?") && (1 == is_loggedin ? $.ajax({
            url: base_url + "cart/remove",
            type: "POST",
            data: {product_variant_id: e, is_save_for_later: i, [csrfName]: csrfHash},
            dataType: "json",
            success: function (t) {
                var e;
                csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (e = $("#cart-count").text(), e--, $("#cart-count").text(e), c(n)) : Toast.fire({
                    icon: "error",
                    title: t.message
                })
            }
        }) : (c(n), t = localStorage.getItem("cart"), (t = null !== localStorage.getItem("cart") ? JSON.parse(t) : null) && (i = t.filter(function (t) {
            return t.product_variant_id != e
        }), localStorage.setItem("cart", JSON.stringify(i)), t && display_cart(i))))
    })
}), $(".js-menu").on("click", () => {
    $(".js-menu").toggleClass("active"), $(".js-filter-nav").toggleClass("open"), $(".js-filter-nav__list").toggleClass("show"), "hidden" == $("body").css("overflow").toLowerCase() ? $("body").css("overflow", "scroll") : $("body").css("overflow", "hidden")
}), jQuery(document).ready(function (n) {
    function t(t) {
        this.element = t, this.mainNavigation = this.element.find(".main-nav"), this.mainNavigationItems = this.mainNavigation.find(".has-dropdown"), this.dropdownList = this.element.find(".dropdown-list"), this.dropdownWrappers = this.dropdownList.find(".dropdown"), this.dropdownItems = this.dropdownList.find(".content"), this.dropdownBg = this.dropdownList.find(".bg-layer"), this.mq = this.checkMq(), this.bindEvents()
    }

    t.prototype.checkMq = function () {
        return window.getComputedStyle(this.element.get(0), "::before").getPropertyValue("content").replace(/'/g, "").replace(/"/g, "").split(", ")
    }, t.prototype.bindEvents = function () {
        var i = this;
        this.mainNavigationItems.mouseenter(function (t) {
            i.showDropdown(n(this))
        }).mouseleave(function () {
            setTimeout(function () {
                0 == i.mainNavigation.find(".has-dropdown:hover").length && 0 == i.element.find(".dropdown-list:hover").length && i.hideDropdown()
            }, 50)
        }), this.dropdownList.mouseleave(function () {
            setTimeout(function () {
                0 == i.mainNavigation.find(".has-dropdown:hover").length && 0 == i.element.find(".dropdown-list:hover").length && i.hideDropdown()
            }, 50)
        }), this.mainNavigationItems.on("touchstart", function (t) {
            var e = i.dropdownList.find("#" + n(this).data("content"));
            i.element.hasClass("is-dropdown-visible") && e.hasClass("active") || (t.preventDefault(), i.showDropdown(n(this)))
        })
    }, t.prototype.showDropdown = function (t) {
        var e, i, n, o, s, a;
        this.mq = this.checkMq(), "desktop" == this.mq && (n = (i = (e = this).dropdownList.find("#" + t.data("content"))).innerHeight() + 18, 540 < (a = 180 * i.children(".content").children("ul").children("li").length) && (a = 540), o = parseInt(a), s = t.offset().left + t.innerWidth() / 2 - o / 2, a = t[0].offsetParent.offsetLeft, this.updateDropdown(i, parseInt(n), o, parseInt(s)), this.element.find(".active").removeClass("active"), this.element.find(".morph-dropdown-wrapper").css({
            "-moz-transform": "translateX(-" + a + "px)",
            "-webkit-transform": "translateX(-" + a + "px)",
            "-ms-transform": "translateX(-" + a + "px)",
            "-o-transform": "translateX(-" + a + "px)",
            transform: "translateX(-" + a + "px)"
        }), i.addClass("active").removeClass("move-left move-right").prevAll().addClass("move-left").end().nextAll().addClass("move-right"), t.addClass("active"), this.element.hasClass("is-dropdown-visible") || setTimeout(function () {
            e.element.addClass("is-dropdown-visible")
        }, 10))
    }, t.prototype.updateDropdown = function (t, e, i, n) {
        this.dropdownList.css({
            "-moz-transform": "translateX(" + n + "px)",
            "-webkit-transform": "translateX(" + n + "px)",
            "-ms-transform": "translateX(" + n + "px)",
            "-o-transform": "translateX(" + n + "px)",
            transform: "translateX(" + n + "px)",
            width: i + "px",
            height: e + "px"
        }), this.dropdownBg.css({
            "-moz-transform": "scaleX(" + i + ") scaleY(" + e + ")",
            "-webkit-transform": "scaleX(" + i + ") scaleY(" + e + ")",
            "-ms-transform": "scaleX(" + i + ") scaleY(" + e + ")",
            "-o-transform": "scaleX(" + i + ") scaleY(" + e + ")",
            transform: "scaleX(" + i + ") scaleY(" + e + ")"
        })
    }, t.prototype.hideDropdown = function () {
        this.mq = this.checkMq(), "desktop" == this.mq && this.element.removeClass("is-dropdown-visible").find(".active").removeClass("active").end().find(".move-left").removeClass("move-left").end().find(".move-right").removeClass("move-right")
    }, t.prototype.resetDropdown = function () {
        this.mq = this.checkMq(), "mobile" == this.mq && this.dropdownList.removeAttr("style")
    };
    var e, i = [];

    function o() {
        i.forEach(function (t) {
            t.resetDropdown()
        }), e = !1
    }

    0 < n(".cd-morph-dropdown").length && (n(".cd-morph-dropdown").each(function () {
        i.push(new t(n(this)))
    }), e = !1, o(), n(window).on("resize", function () {
        e || (e = !0, window.requestAnimationFrame ? window.requestAnimationFrame(o) : setTimeout(o, 300))
    }))
}), $(".navbar-top-search-box input").on("focus", function () {
    $(".navbar-top-search-box .input-group-text").css("border-color", "#0e7dd1")
}), $(".navbar-top-search-box input").on("blur", function () {
    $(".navbar-top-search-box .input-group-text").css("border", "1px solid #ced4da")
});
var swiper = new Swiper(".swiper1", {
        loop: !0,
        preloadImages: !1,
        lazy: !0,
        autoplay: {delay: 6e3, disableOnInteraction: !1},
        pagination: {el: ".swiper1-pagination", clickable: !0},
        navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"}
    }), swiperheader = new Swiper(".imageSliderHeader", {
        autoplay: {delay: 6e3, disableOnInteraction: !1},
        pagination: {el: ".imageSliderHeader-pagination", clickable: !0},
        loop: !0,
        grabCursor: !0
    }), swiperF = new Swiper(".preview-image-swiper", {pagination: {el: ".preview-image-swiper-pagination"}}),
    swiperV = new Swiper(".banner-swiper", {
        preloadImages: !1,
        lazy: !0,
        autoplay: !0,
        pagination: {el: ".banner-swiper-pagination"},
        loop: !0,
        navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"}
    }), swiperS = new Swiper(".category-swiper", {
        slidesPerView: 5,
        preloadImages: !1,
        lazyLoading: !0,
        updateOnImagesReady: !1,
        lazyLoadingInPrevNextAmount: 0,
        pagination: {el: ".category-swiper-pagination", clickable: !0},
        breakpoints: {
            350: {slidesPerView: 4, spaceBetweenSlides: 10},
            400: {slidesPerView: 4, spaceBetweenSlides: 10},
            499: {slidesPerView: 4, spaceBetweenSlides: 10},
            550: {slidesPerView: 1, spaceBetweenSlides: 10},
            600: {slidesPerView: 2, spaceBetweenSlides: 10},
            700: {slidesPerView: 3, spaceBetweenSlides: 10},
            800: {slidesPerView: 4, spaceBetweenSlides: 10},
            999: {slidesPerView: 5, spaceBetweenSlides: 10},
            1900: {slidesPerView: 6, spaceBetweenSlides: 10}
        }
    });
document.querySelectorAll(".product-image-swiper").forEach(function (t) {
    new Swiper(t, {
        grabCursor: !0,
        preloadImages: !1,
        lazyLoading: !0,
        updateOnImagesReady: !1,
        lazyLoadingInPrevNextAmount: 1,
        navigation: {nextEl: t.nextElementSibling, prevEl: t.nextElementSibling.nextElementSibling},
        breakpoints: {
            350: {slidesPerView: 1, spaceBetweenSlides: 10},
            400: {slidesPerView: 1, spaceBetweenSlides: 10},
            499: {slidesPerView: 1, spaceBetweenSlides: 10},
            550: {slidesPerView: 1, spaceBetweenSlides: 10},
            600: {slidesPerView: 2, spaceBetweenSlides: 10},
            700: {slidesPerView: 3, spaceBetweenSlides: 10},
            800: {slidesPerView: 4, spaceBetweenSlides: 10},
            999: {slidesPerView: 5, spaceBetweenSlides: 10},
            1900: {slidesPerView: 6, spaceBetweenSlides: 10}
        }
    })
});
var timer, swiperH = new Swiper(".swiper2", {
    slidesPerView: "auto",
    grabCursor: !0,
    spaceBetween: 20,
    pagination: {el: ".swiper2-pagination", clickable: !0}
}), galleryThumbs = new Swiper(".gallery-thumbs-1", {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: !0,
    watchSlidesVisibility: !0,
    watchSlidesProgress: !0
}), galleryTop = new Swiper(".gallery-top-1", {
    spaceBetween: 10,
    navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"},
    thumbs: {swiper: galleryThumbs}
});

function openNav() {
    $(".block-div").css("width", "100%"), $("body").css("overflow", "hidden"), $("#mySidenav").removeClass("is-closed-left")
}

function openCartSidebar() {
    $(".block-div").css("width", "100%"), $("body").css("overflow", "hidden"), $(".shopping-cart-sidebar").removeClass("is-closed-right")
}

function closeNav() {
    $(".block-div").css("width", "0%"), $("body").css("overflow", "unset"), $(".shopping-cart-sidebar").addClass("is-closed-right"), $("#mySidenav").addClass("is-closed-left")
}

$(document).ready(function () {
    var t = $("img#img_01");
    t.ezPlus(), t.bind("click", function (t) {
        $("#img_01").data("ezPlus");
        return !1
    })
}), $(document).ready(function () {
    jQuery(document).ready(function () {
        jQuery("#jquery-accordion-menu").jqueryAccordionMenu(), jQuery(".colors a").click(function () {
            "default" != $(this).attr("class") ? ($("#jquery-accordion-menu").removeClass(), $("#jquery-accordion-menu").addClass("jquery-accordion-menu").addClass($(this).attr("class"))) : ($("#jquery-accordion-menu").removeClass(), $("#jquery-accordion-menu").addClass("jquery-accordion-menu"))
        })
    })
}), function (o, e) {
    var i = "jqueryAccordionMenu", n = {speed: 300, showDelay: 0, hideDelay: 0, singleOpen: !0, clickEffect: !0};

    function s(t, e) {
        this.element = t, this.settings = o.extend({}, n, e), this._defaults = n, this._name = i, this.init()
    }

    o.extend(s.prototype, {
        init: function () {
            this.openSubmenu(), this.submenuIndicators(), n.clickEffect && this.addClickEffect()
        }, openSubmenu: function () {
            o(this.element).children("ul").find("li").bind("click touchstart", function (t) {
                if (t.stopPropagation(), t.preventDefault(), 0 < o(this).children(".submenu").length) {
                    if ("none" == o(this).children(".submenu").css("display")) return o(this).children(".submenu").show(n.speed), o(this).children(".submenu").siblings("a").addClass("submenu-indicator-minus"), n.singleOpen && (o(this).siblings().children(".submenu").hide(n.speed), o(this).siblings().children(".submenu").siblings("a").removeClass("submenu-indicator-minus")), !1;
                    o(this).children(".submenu").delay(n.hideDelay).hide(n.speed), o(this).children(".submenu").siblings("a").hasClass("submenu-indicator-minus") && o(this).children(".submenu").siblings("a").removeClass("submenu-indicator-minus")
                }
                e.location.href = o(this).children("a").attr("href")
            })
        }, submenuIndicators: function () {
            0 < o(this.element).find(".submenu").length && o(this.element).find(".submenu").siblings("a").append("<span class='submenu-indicator'>+</span>")
        }, addClickEffect: function () {
            var e, i, n;
            o(this.element).find("a > .submenu-indicator").on("click touchstart", function (t) {
                o(".ink").remove(), 0 === o(this).children(".ink").length && o(this).prepend("<span class='ink'></span>"), (e = o(this).find(".ink")).removeClass("animate-ink"), e.height() || e.width() || (n = Math.max(o(this).outerWidth(), o(this).outerHeight()), e.css({
                    height: n,
                    width: n
                })), i = t.pageX - o(this).offset().left - e.width() / 2, n = t.pageY - o(this).offset().top - e.height() / 2, e.css({
                    top: n + "px",
                    left: i + "px"
                }).addClass("animate-ink")
            })
        }
    }), o.fn[i] = function (t) {
        return this.each(function () {
            o.data(this, "plugin_" + i) || o.data(this, "plugin_" + i, new s(this, t))
        }), this
    }
}(jQuery, window, document), document.addEventListener("DOMContentLoaded", function (t) {
    const e = document.querySelectorAll(".cart-button");

    function i() {
        this.classList.add("clicked")
    }

    e.forEach(t => {
        t.addEventListener("click", i)
    })
});
var compareDate = new Date;

function timeBetweenDates(t) {
    var e = t, i = new Date, n = e.getTime() - i.getTime();
    n <= 0 ? clearInterval(timer) : (t = Math.floor(n / 1e3), e = Math.floor(t / 60), i = Math.floor(e / 60), n = Math.floor(i / 24), i %= 24, e %= 60, t %= 60, $("#days").text(n), $("#hours").text(i), $("#minutes").text(e), $("#seconds").text(t))
}

compareDate.setDate(compareDate.getDate() + 7), timer = setInterval(function () {
    timeBetweenDates(compareDate)
}, 1e3), $(window).scroll(function () {
    50 < $(this).scrollTop() ? $(".back-to-top:hidden").stop(!0, !0).fadeIn() : $(".back-to-top").stop(!0, !0).fadeOut()
}), $(function () {
    $(".scroll").click(function () {
        return $("html,body").animate({scrollTop: $(".sidenav").offset().top}, "1000"), !1
    })
}), $("#newsletter-modal").on("show.bs.modal", function (t) {
    $(t.relatedTarget).data("whatever")
});
swiper = new Swiper(".swiper-container-client", {
    loop: !0,
    loopedSlides: 10,
    autoheight: !0,
    slidesPerView: 2,
    spaceBetween: 30,
    autoplay: {delay: 6e3, disableOnInteraction: !1},
    breakpoints: {600: {slidesPerView: 6, spaceBetween: 20}},
    pagination: {el: ".swiper-pagination", clickable: !0}
});

function buildUrlParameterValue(t, e, i, n = "") {
    return t = "" != n ? getUrlParameter(t, n) : getUrlParameter(t), "add" == i ? (null == t ? t = e : t += "|" + e, t) : "remove" == i ? null != t ? ((t = t.split("|")).splice($.inArray(e, t), 1), t.join("|")) : "" : void 0
}

function getUrlParameter(t, e = "") {
    if (t = t.replace(/\s+/g, "-"), "" != e) {
        if (!(-1 < e.indexOf("?"))) return;
        var i = e.substring(e.indexOf("?") + 1)
    } else i = window.location.search.substring(1);
    for (var n, o = i.split("&"), s = 0; s < o.length; s++) if ((n = o[s].split("="))[0] === t) return void 0 === n[1] || decodeURIComponent(n[1])
}

function checkUrlHasParam(t = "") {
    if (-1 < (t = "" == t ? window.location.href : t).indexOf("?")) return !0
}

function setUrlParameter(t, e, i) {
    if (e = e.replace(/\s+/g, "-"), null == i || "" == i) return t.replace(new RegExp("[?&]" + e + "=[^&#]*(#.*)?$"), "$1").replace(new RegExp("([?&])" + e + "=[^&]*&"), "$1");
    var n = new RegExp("\\b(" + e + "=).*?(&|#|$)");
    return 0 <= t.search(n) ? t.replace(n, "$1" + i + "$2") : (t = t.replace(/[?#]$/, "")) + (0 < t.indexOf("?") ? "&" : "?") + e + "=" + i
}

jQuery(document).ready(function (e) {
    e("ul.color-style .default").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/default.css"), !1
    }), e("ul.color-style .peach").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/peach.css"), !1
    }), e("ul.color-style .yellow").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/yellow.css"), !1
    }), e("ul.color-style .green").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/green.css"), !1
    }), e("ul.color-style .purple").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/purple.css"), !1
    }), e("ul.color-style .red").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/red.css"), !1
    }), e("ul.color-style .dark-blue").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/dark-blue.css"), !1
    }), e("ul.color-style .orange").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/orange.css"), !1
    }), e("ul.color-style .cyan-dark").click(function () {
        return e("#color-switcher").attr("href", base_url + "assets/front_end/classic/css/colors/cyan-dark.css"), !1
    }), e("ul.color-style li a").click(function (t) {
        t.preventDefault(), e(this).parent().parent().find("a").removeClass("active"), e(this).addClass("active")
    }), e("#colors-switcher .color-bottom a.settings").click(function (t) {
        t.preventDefault(), "-189px" === e("#colors-switcher").css(mode) ? e("#colors-switcher").animate({[mode]: "0px"}) : e("#colors-switcher").animate({[mode]: "-189px"})
    }), e("#colors-switcher").animate({[mode]: "-189px"})
}), $("#back_to_top").on("click", function () {
    $("html, body").animate({scrollTop: 0}, "slow")
}), $("#per_page_products a").on("click", function (t) {
    t.preventDefault();
    t = $(this).data("value");
    $(this).parent().siblings("a.dropdown-toggle").text($(this).text()), location.href = setUrlParameter(location.href, "per-page", t)
}), $("#per_page_sellers a").on("click", function (t) {
    t.preventDefault();
    t = $(this).data("value");
    $(this).parent().siblings("a.dropdown-toggle").text($(this).text()), location.href = setUrlParameter(location.href, "per-page", t)
}), $("#product_sort_by").on("change", function (t) {
    t.preventDefault();
    t = $(this).val();
    location.href = setUrlParameter(location.href, "sort", t)
}), $("#seller_search").on("keyup", function (t) {
    t.preventDefault();
    t = $(this).val();
    location.href = setUrlParameter(location.href, "seller_search", t)
}), $(".sub-category").on("click", function (t) {
    t.preventDefault();
    t = $(this).data("value");
    custom_url = setUrlParameter(custom_url, "category", t), location.href = custom_url
}), $(document).on("change", ".product_attributes", function (t) {
    t.preventDefault();
    getUrlParameter(e = "filter-" + (e = $(this).data("attribute")));
    var e, t = $(this).val();
    t = this.checked ? buildUrlParameterValue(e, t, "add", custom_url) : buildUrlParameterValue(e, t, "remove", custom_url), custom_url = setUrlParameter(custom_url, e, t)
}), $(".product_filter_btn").on("click", function (t) {
    t.preventDefault(), location.href = custom_url
});
var filters, type_url = "";

function arrays_equal(t, e) {
    if (!Array.isArray(t) || !Array.isArray(e) || t.length !== e.length) return !1;
    var i = t.concat().sort(), n = e.concat().sort();
    for (let t = 0; t < i.length; t++) if (i[t] !== n[t]) return !1;
    return !0
}

function display_cart(t) {
    var e, i;
    0 == is_loggedin && (e = t.length || "", $("#cart-count").text(e), i = "", null !== t && 0 < t.length && t.forEach(t => {
        i += '<div class="row"><div class="cart-product product-sm col-md-12"><div class="product-image"><img class="pic-1" src="' + t.image + '" alt="Not Found"></div><div class="product-details"><div class="product-title">' + t.title + '</div><p class="product-descriptions">' + t.description + '</p></div><div class="product-pricing d-flex py-2 px-1 w-100"><div class="product-price align-self-center">' + currency + " " + t.price + '</div><div class="product-sm-quantity px-1"><input type="number" class="form-input" value="' + t.qty + '"  data-id="' + t.product_variant_id + '" data-price="' + t.price + '"min="' + t.min + '"  step="' + t.step + '"></div><div class="product-sm-removal align-self-center"><button class="remove-product button button-danger" data-id="' + t.product_variant_id + '"><i class="fa fa-trash"></i></button></div><div class="product-line-price align-self-center px-1">' + currency + " " + (t.qty * t.price).toLocaleString(void 0, {minimumFractionDigits: 2}) + "</div></div></div></div>"
    }), $("#cart-item-sidebar").html(i))
}

function cart_sync() {
    var t = localStorage.getItem("cart");
    null != t && t ? $.ajax({
        type: "POST",
        url: base_url + "cart/cart_sync",
        data: {[csrfName]: csrfHash, data: t, is_saved_for_later: !1},
        dataType: "json",
        success: function (t) {
            if (csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error) return Toast.fire({
                icon: "success",
                title: t.message
            }), localStorage.removeItem("cart"), !0
        }
    }) : console.log("No items in cart so it will not be sync")
}

function transaction_query_params(t) {
    return {
        transaction_type: "transaction",
        user_id: $("#transaction_user_id").val(),
        limit: t.limit,
        sort: t.sort,
        order: t.order,
        offset: t.offset,
        search: t.search
    }
}

function customer_wallet_query_params(t) {
    return {
        transaction_type: "wallet",
        limit: t.limit,
        sort: t.sort,
        order: t.order,
        offset: t.offset,
        search: t.search
    }
}

function print_filters(t, i = "", e) {
    var n, o, s, a, r, l, c = "";
    "" != t && $.each(JSON.parse(t), function (t, e) {
        l = e.name.replace(" ", "-").toLowerCase(), l = decodeURIComponent(l), o = getUrlParameter("filter-" + l), s = null == o ? " " : "show", a = null != o ? o.split("|") : "", c += '<div class="card-custom"><div class="card-header-custom" id="h' + t + '"><h2 class="clearfix mb-0"><a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#' + i + t + '" aria-expanded="true" aria-controls="#' + i + t + '">' + e.name + '<i class="fa fa-angle-down rotate"></i></a></h2></div><div id="' + i + t + '" class="collapse ' + s + '" aria-labelledby="h' + t + '" data-parent="#accordionExample"><div class="card-body-custom">', e.attribute_values_id.split(","), s = e.attribute_values.split(","), $.each(s, function (t, e) {
            r = -1 !== $.inArray(e, a) ? "checked" : "", c += '<div class="input-container d-flex"><input type="checkbox" name="' + e + '" value="' + e + '" class="toggle-input product_attributes" id="' + i + (n = l + " " + e) + '" data-attribute="' + l + '" ' + r + '><label class="toggle checkbox" for="' + i + n + '"><div class="toggle-inner"></div></label><label for="' + i + n + '" class="text-label">' + e + "</label></div>"
        }), c += "</div></div></div>"
    }), $(e).html(c)
}

type_url = setUrlParameter(custom_url, "type", null), $("#product_grid_view_btn").attr("href", type_url), type_url = setUrlParameter(custom_url, "type", "list"), $("#product_list_view_btn").attr("href", type_url), ("list" == getUrlParameter("type") ? $("#product_list_view_btn") : $("#product_grid_view_btn")).addClass("active"), $("#category_parent").each(function () {
    $(this).select2({
        theme: "bootstrap4",
        width: $(this).data("width") ? $(this).data("width") : $(this).hasClass("w-100") ? "100%" : "style",
        placeholder: $(this).data("placeholder"),
        allowClear: Boolean($(this).data("allow-clear")),
        dropdownCssClass: "test",
        templateResult: function (t) {
            if (!t.element) return t.text;
            var e = $(t.element), i = $("<span></span>");
            return i.addClass(e[0].className), i.text(t.text), i
        }
    })
}), $("#category_parent").on("change", function (t) {
    t.preventDefault();
    t = $(this).val();
    location.href = setUrlParameter(location.href, "category_id", t)
}), $("#blog_search").on("keyup", function (t) {
    t.preventDefault();
    t = $(this).val();
    location.href = setUrlParameter(location.href, "blog_search", t)
}), $(".auth_model").on("click", function (t) {
    t.preventDefault();
    t = $(this).data("value");
    $("#forgot_password_div").addClass("hide"), "login" == t ? ($("#login_div").removeClass("hide"), $("#login").addClass("active"), $("#register_div").addClass("hide"), $("#register").removeClass("active")) : "register" == t && ($("#login_div").addClass("hide"), $("#login").removeClass("active"), $("#register_div").removeClass("hide"), $("#register").addClass("active"))
}), $(".attributes").on("change", function (t) {
    t.preventDefault();
    var n, o, s = [], a = "", r = !1, e = [], l = [], c = [], d = [], u = [], h = [];
    $(".variants").each(function () {
        l = {
            price: $(this).data("price"),
            special_price: $(this).data("special_price")
        }, u.push($(this).data("id")), c.push(l), e = $(this).val().split(","), d.push(e), h.push($(this).data("image-index"))
    }), n = e.length, $(".attributes").each(function (t, e) {
        var i;
        $(this).prop("checked") && (s.push($(this).val()), s.length == n && (l = [], i = "", $.each(d, function (t, e) {
            arrays_equal(s, e) && (r = !0, l.push(c[t]), i = u[t], o = h[t])
        }), r ? ($("#add_cart").attr("data-product-variant-id", i), galleryTop.slideTo(o, 500, !1), swiperF.slideTo(o, 500, !1), l[0].special_price < l[0].price && 0 != l[0].special_price ? (a = l[0].special_price, $("#price").html(currency + " " + a), $("#striped-price").html(currency + " " + l[0].price), $("#striped-price-div").show()) : (a = l[0].price, $("#price").html(currency + " " + a), $("#striped-price-div").hide()), $("#add_cart").removeAttr("disabled")) : (a = '<small class="text-danger h5">No Variant available!</small>', $("#price").html(a), $("#striped-price-div").hide(), $("#striped-price").html(""), $("#add_cart").attr("disabled", "true"))))
    }), d = ""
}), $(document).on("click", ".add_to_cart", function (t) {
    t.preventDefault();
    var e = $('[name="qty"]').val();
    $("#quick-view").data("data-product-id", $(this).data("productId"));
    var n = $(this).attr("data-product-variant-id"), s = $(this).attr("data-product-title"),
        a = $(this).attr("data-product-image"), r = $(this).attr("data-product-price"),
        l = $(this).attr("data-product-description"), c = $(this).attr("data-min"), d = $(this).attr("data-max"),
        u = $(this).attr("data-step"), h = $(this), p = $(this).html(), t = $(this).attr("data-izimodal-open");
    n ? "" != t && null != t || $.ajax({
        type: "POST",
        url: base_url + "cart/manage",
        data: {product_variant_id: n, qty: e, is_saved_for_later: !1, [csrfName]: csrfHash},
        dataType: "json",
        beforeSend: function () {
            h.html("Please Wait").text("Please Wait").attr("disabled", !0)
        },
        success: function (t) {
            if (csrfName = t.csrfName, csrfHash = t.csrfHash, h.html(p).attr("disabled", !1), 0 == t.error) {
                Toast.fire({icon: "success", title: t.message}), $("#cart-count").text(t.data.cart_count);
                var o = "";
                $.each(t.data.items, function (t, e) {
                    var i = void 0 !== e.product_variants.variant_values && null != e.product_variants.variant_values ? e.product_variants.variant_values : "",
                        n = e.special_price < e.price && 0 != e.special_price ? e.special_price : e.price;
                    o += '<div class="row"><div class="cart-product product-sm col-md-12"><div class="product-image"><img class="pic-1" src="' + base_url + e.image + '" alt="Not Found"></div><div class="product-details"><div class="product-title">' + e.name + "</div><span>" + i + '</span><p class="product-descriptions">' + e.short_description + '</p></div><div class="product-pricing d-flex py-2 px-1 w-100"><div class="product-price align-self-center">' + currency + " " + n + '</div><div class="product-sm-quantity px-1"><input type="number" class="form-input" value="' + c + '"  data-id="' + e.product_variant_id + '" data-price="' + n + '" min="' + c + '" max="' + d + '" step="' + u + '" ></div><div class="product-sm-removal align-self-center"><button class="remove-product button button-danger" data-id="' + e.product_variant_id + '"><i class="fa fa-trash"></i></button></div><div class="product-line-price align-self-center px-1">' + currency + " " + (e.qty * n).toLocaleString(void 0, {minimumFractionDigits: 2}) + "</div></div></div></div>"
                }), $("#cart-item-sidebar").html(o)
            } else {
                if (0 == is_loggedin) {
                    Toast.fire({icon: "success", title: "Item added to cart"});
                    var e = {
                        product_variant_id: n.trim(),
                        title: s,
                        description: l,
                        qty: c,
                        image: a,
                        price: r.trim(),
                        min: c,
                        step: u
                    }, i = localStorage.getItem("cart");
                    return null != (i = null !== localStorage.getItem("cart") ? JSON.parse(i) : null) ? i.push(e) : i = [e], localStorage.setItem("cart", JSON.stringify(i)), void display_cart(i)
                }
                Toast.fire({icon: "error", title: t.message})
            }
        }
    }) : Toast.fire({icon: "error", title: "Please select variant"})
}), $(document).ready(function () {
    var t = localStorage.getItem("cart");
    (t = null !== localStorage.getItem("cart") ? JSON.parse(t) : null) && display_cart(t)
}), $(document).ready(function () {
    $(document).on("click", "#clear_cart", function () {
        confirm("Are you sure want to Clear Cart?") && $.ajax({
            type: "POST",
            data: {[csrfName]: csrfHash},
            url: base_url + "cart/clear",
            success: function (t) {
                csrfName = t.csrfName, csrfHash = t.csrfHash, location.reload()
            }
        })
    }), $(document).on("click", "#checkout", function (t) {
        confirm("Are You Sure want to Checkout?") || t.preventDefault()
    })
}), $(".quick-view-btn").on("click", function () {
    $("#quick-view").data("data-product-id", $(this).data("productId"))
}), $(".save-for-later").on("click", function (t) {
    t.preventDefault();
    var e = new FormData, i = $(this).data("id"),
        t = $(this).parent().siblings(".item-quantity").find(".itemQty").val();
    $(this);
    e.append(csrfName, csrfHash), e.append("product_variant_id", i), e.append("is_saved_for_later", 1), e.append("qty", t), $.ajax({
        type: "POST",
        url: base_url + "cart/manage",
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? window.location.reload() : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    })
}), $(".move-to-cart").on("click", function (t) {
    t.preventDefault();
    var e = new FormData, i = $(this).data("id"), t = $(this).parent().parent().siblings(".itemQty").text();
    $(this);
    e.append(csrfName, csrfHash), e.append("product_variant_id", i), e.append("is_saved_for_later", 0), e.append("qty", t), $.ajax({
        type: "POST",
        url: base_url + "cart/manage",
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? window.location.reload() : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    })
}), $(".update-order-item").on("click", function (t) {
    t.preventDefault();
    var e = new FormData, i = $(this).data("item-id"), t = $(this).data("status"), n = $(this), o = n.text();
    e.append(csrfName, csrfHash), e.append("order_item_id", i), e.append("status", t), $.ajax({
        type: "POST",
        url: base_url + "my-account/update-order-item-status",
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        beforeSend: function () {
            n.html("Please Wait").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), setTimeout(function () {
                window.location.reload()
            }, 3e3)) : Toast.fire({icon: "error", title: t.message}), n.html(o).attr("disabled", !1)
        }
    })
}), $(".update-order").on("click", function (t) {
    t.preventDefault();
    var e, i, n = new FormData, o = $(this).data("order-id"), s = $(this).data("status"), t = "",
        t = "cancelled" == s ? "Cancel" : "Return";
    confirm("Are you sure you want to " + t + " this order ?") && (e = $(this), i = e.text(), n.append(csrfName, csrfHash), n.append("order_id", o), n.append("status", s), $.ajax({
        type: "POST",
        url: base_url + "my-account/update-order",
        data: n,
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        beforeSend: function () {
            e.html("Please Wait").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), setTimeout(function () {
                window.location.reload()
            }, 3e3)) : Toast.fire({icon: "error", title: t.message}), e.html(i).attr("disabled", !1)
        }
    }))
}), $("#add-address-form").on("submit", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        data: t,
        url: $(this).attr("action"),
        dataType: "json",
        cache: !1,
        contentType: !1,
        processData: !1,
        beforeSend: function () {
            $("#save-address-submit-btn").val("Please Wait...").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? ($("#save-address-result").html("<div class='alert alert-success'>" + t.message + "</div>").delay(1500).fadeOut(), $("#add-address-form")[0].reset(), $("#address_list_table").bootstrapTable("refresh")) : $("#save-address-result").html("<div class='alert alert-danger'>" + t.message + "</div>").delay(1500).fadeOut(), $("#save-address-submit-btn").val("Save").attr("disabled", !1)
        }
    })
}), $("#city").on("change", function (t) {
    t.preventDefault(), $.ajax({
        type: "POST",
        data: {city_id: $(this).val(), [csrfName]: csrfHash},
        url: base_url + "my-account/get-areas",
        dataType: "json",
        success: function (t) {
            var i;
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (i = "", i += '<option value="">--Select Area--</option>', $.each(t.data, function (t, e) {
                i += "<option value=" + e.id + ">" + e.name + "</option>"
            }), $("#area").html(i)) : (Toast.fire({icon: "error", title: t.message}), $("#area").html(""))
        }
    })
}), $("#area").on("change", function (t) {
    t.preventDefault(), $.ajax({
        type: "POST",
        data: {area_id: $(this).val(), [csrfName]: csrfHash},
        url: base_url + "my-account/get-zipcode",
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? $("#pincode").val(t.data[0].zipcode) : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    })
}), $("#edit-address-form").on("submit", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        data: t,
        url: $(this).attr("action"),
        dataType: "json",
        cache: !1,
        contentType: !1,
        processData: !1,
        beforeSend: function () {
            $("#edit-address-submit-btn").val("Please Wait...").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? ($("#edit-address-result").html("<div class='alert alert-success'>" + t.message + "</div>").delay(1500).fadeOut(), $("#edit-address-form")[0].reset(), $("#address_list_table").bootstrapTable("refresh"), setTimeout(function () {
                $("#address-modal").modal("hide")
            }, 2e3)) : $("#edit-address-result").html("<div class='alert alert-danger'>" + t.message + "</div>").delay(1500).fadeOut(), $("#edit-address-submit-btn").val("Save").attr("disabled", !1)
        }
    })
}), $(document).on("click", ".delete-address", function (t) {
    t.preventDefault(), confirm("Are you sure ? You want to delete this address?") && $.ajax({
        type: "POST",
        data: {id: $(this).data("id"), [csrfName]: csrfHash},
        url: base_url + "my-account/delete-address",
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? $("#address_list_table").bootstrapTable("refresh") : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    })
}), $(document).on("click", ".default-address", function (t) {
    t.preventDefault(), confirm("Are you sure ? You want to set this address as default?") && $.ajax({
        type: "POST",
        data: {id: $(this).data("id"), [csrfName]: csrfHash},
        url: base_url + "my-account/set-default-address",
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? ($("#address_list_table").bootstrapTable("refresh"), Toast.fire({
                icon: "success",
                title: t.message
            })) : Toast.fire({icon: "error", title: t.message})
        }
    })
}), $(document).on("click", "#forgot_password_link", function (t) {
    t.preventDefault(), $(".auth-modal").find("header a").removeClass("active"), $("#forgot_password_div").removeClass("hide").siblings("section").addClass("hide"), $("#recaptcha-container-2").html(""), window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier("recaptcha-container-2"), window.recaptchaVerifier.render().then(function (t) {
        grecaptcha.reset(t)
    }), $("#forgot_password_number").intlTelInput({
        allowExtensions: !0,
        formatOnDisplay: !0,
        autoFormat: !0,
        autoHideDialCode: !0,
        autoPlaceholder: !0,
        defaultCountry: "in",
        ipinfoToken: "yolo",
        nationalMode: !1,
        numberType: "MOBILE",
        preferredCountries: ["in", "ae", "qa", "om", "bh", "kw", "ma"],
        preventInvalidNumbers: !0,
        separateDialCode: !0,
        initialCountry: "auto",
        geoIpLookup: function (e) {
            $.get("https://ipinfo.io", function () {
            }, "jsonp").always(function (t) {
                t = t && t.country ? t.country : "";
                e(t)
            })
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
    })
}), $(document).on("submit", "#send_forgot_password_otp_form", function (t) {
    t.preventDefault();
    var e = $("#forgot_password_send_otp_btn").html();
    $("#forgot_password_send_otp_btn").html("Please Wait...").attr("disabled", !0);
    var i = $(".selected-dial-code").html() + $("#forgot_password_number").val(),
        n = is_user_exist($("#forgot_password_number").val());
    0 == n.error ? ($("#forgot_pass_error_box").html("You have not registered using this number."), $("#forgot_password_send_otp_btn").html(e).attr("disabled", !1)) : (t = window.recaptchaVerifier, firebase.auth().signInWithPhoneNumber(i, t).then(function (o) {
        resetRecaptcha(), $("#verify_forgot_password_otp_form").removeClass("d-none"), $("#send_forgot_password_otp_form").hide(), $("#forgot_pass_error_box").html(n.message), $("#forgot_password_send_otp_btn").html(e).attr("disabled", !1), $(document).on("submit", "#verify_forgot_password_otp_form", function (t) {
            t.preventDefault();
            var e = $("#reset_password_submit_btn").html(), t = $("#forgot_password_otp").val(), i = new FormData(this),
                n = base_url + "home/reset-password";
            $("#reset_password_submit_btn").html("Please Wait...").attr("disabled", !0), o.confirm(t).then(function (t) {
                i.append(csrfName, csrfHash), i.append("mobile", $("#forgot_password_number").val()), $.ajax({
                    type: "POST",
                    url: n,
                    data: i,
                    processData: !1,
                    contentType: !1,
                    cache: !1,
                    dataType: "json",
                    beforeSend: function () {
                        $("#reset_password_submit_btn").html("Please Wait...").attr("disabled", !0)
                    },
                    success: function (t) {
                        csrfName = t.csrfName, csrfHash = t.csrfHash, $("#reset_password_submit_btn").html(e).attr("disabled", !1), $("#set_password_error_box").html(t.message).show(), 0 == t.error && setTimeout(function () {
                            window.location.reload()
                        }, 2e3)
                    }
                })
            }).catch(function (t) {
                $("#reset_password_submit_btn").html(e).attr("disabled", !1), $("#set_password_error_box").html("Invalid OTP. Please Enter Valid OTP").show()
            })
        })
    }).catch(function (t) {
        $("#forgot_pass_error_box").html(t.message).show(), $("#forgot_password_send_otp_btn").html(e).attr("disabled", !1), resetRecaptcha()
    }))
}), $("#contact-us-form").on("submit", function (t) {
    t.preventDefault();
    var e = $("#contact-us-submit-btn").html(), t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        data: t,
        url: $(this).attr("action"),
        dataType: "json",
        cache: !1,
        contentType: !1,
        processData: !1,
        beforeSend: function () {
            $("#contact-us-submit-btn").html("Please Wait...").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), $("#contact-us-form")[0].reset()) : Toast.fire({
                icon: "error",
                title: t.message
            }), $("#contact-us-submit-btn").html(e).attr("disabled", !1)
        }
    })
}), $("#product-rating-form").on("submit", function (t) {
    t.preventDefault();
    var e = $("#rating-submit-btn").html(), t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        data: t,
        url: $(this).attr("action"),
        dataType: "json",
        cache: !1,
        contentType: !1,
        processData: !1,
        beforeSend: function () {
            $("#rating-submit-btn").html("Please Wait...").attr("disabled", !0)
        },
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), $("#product-rating-form")[0].reset(), window.location.reload()) : Toast.fire({
                icon: "error",
                title: t.message
            }), $("#rating-submit-btn").html(e).attr("disabled", !1)
        }
    })
}), $("#delete_rating").on("click", function (t) {
    t.preventDefault(), confirm("Are you sure want to Delete Rating ?") && (t = $(this).data("rating-id"), $.ajax({
        type: "POST",
        data: {[csrfName]: csrfHash, rating_id: t},
        url: $(this).attr("href"),
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), $("#delete_rating").parent().parent().parent().remove(), $("#no_ratings").text(t.data.rating[0].no_of_rating)) : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    }))
}), $("#edit_link").on("click", function (t) {
    t.preventDefault(), $("#rating-box").removeClass("d-none")
}), $("#load-user-ratings").on("click", function (t) {
    t.preventDefault();
    var e = $(this).attr("data-limit"), i = $(this).attr("data-offset"), t = $(this).attr("data-product"),
        n = $(this).html(), o = $(this), s = "";
    $.ajax({
        type: "GET",
        data: {limit: e, offset: i, product_id: t},
        url: base_url + "products/get-rating",
        dataType: "json",
        beforeSend: function () {
            $(this).html("Please wait..").attr("disabled", !0)
        },
        success: function (t) {
            $(this).html(n).attr("disabled", !1), 0 == t.error ? ($.each(t.data.product_rating, function (t, e) {
                s += '<li class="review-container"><div class="review-image"><img src="' + base_url + 'assets/front_end/modern/images/user.png" alt="" width="65" height="65"></div><div class="review-comment"><div class="rating-list"><div class="product-rating"><input type="text" class="kv-fa" value="' + e.rating + '" data-size="xs" title="" readonly></div></div><div class="review-info"><h4 class="reviewer-name">' + e.user_name + '</h4> <span class="review-date text-muted">' + e.data_added + '</span></div><div class="review-text"><p class="text-muted">' + e.comment + '</p></div><div class="row reviews">', $.each(e.images, function (t, e) {
                    s += '<div class="col-md-2"><div class="review-box"><a href="' + e + '" data-lightbox="review-images"><img src="' + e + '" alt="' + e + '"></a></div></div>'
                }), s += "</div></div></li>"
            }), i += e, $("#review-list").append(s), $(".kv-fa").rating("create", {
                filledStar: '<i class="fas fa-star"></i>',
                emptyStar: '<i class="far fa-star"></i>',
                size: "xs",
                showCaption: !1
            }), o.attr("data-offset", i)) : Toast.fire({icon: "error", title: t.message})
        }
    })
}), $("#edit_city").on("change", function (t, o) {
    t.preventDefault();
    var s = $(this).val();
    $.ajax({
        type: "POST",
        data: {city_id: $(this).val(), [csrfName]: csrfHash},
        url: base_url + "my-account/get-areas",
        dataType: "json",
        success: function (t) {
            var n;
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (n = "", $.each(t.data, function (t, e) {
                var i = e.city_id == s && e.id == o ? "selected" : "";
                n += "<option value=" + e.id + " " + i + ">" + e.name + "</option>"
            }), $("#edit_area").html(n)) : (Toast.fire({icon: "error", title: t.message}), $("#edit_area").html(""))
        }
    })
}), $("#edit_area").on("change", function (t, e) {
    t.preventDefault();
    e = "" == e || "undefined" == e ? $(this).val() : e;
    $.ajax({
        type: "POST",
        data: {area_id: e, [csrfName]: csrfHash},
        url: base_url + "my-account/get-zipcode",
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? $("#edit_pincode").val(t.data[0].zipcode) : Toast.fire({
                icon: "error",
                title: t.message
            })
        }
    })
}), $("#product-filters").length && (checkUrlHasParam() && null != sessionStorage.getItem($("#product-filters").data("key")) || sessionStorage.setItem($("#product-filters").data("key"), $("#product-filters").val()), print_filters(filters = (filters = sessionStorage.getItem($("#product-filters").data("key"))).replace(/\\/g, ""), "Desktop", "#product-filters-desktop"), print_filters(filters, "Mobile", "#product-filters-mobile")), $(document).on("closed", "#quick-view", function (t) {
    $("#modal-product-special-price").html("")
}), window.addEventListener("load", addDarkmodeWidget);
const options = {
    time: "0.5s",
    mixColor: "#fff",
    backgroundColor: "#fff",
    buttonColorDark: "#100f2c",
    buttonColorLight: "#fff",
    label: "🌕",
    autoMatchOsTheme: !1
};

function addDarkmodeWidget() {
    new Darkmode(options).showWidget()
}

function usercartTotal() {
    var e = 0;
    $("#cart_item_table > tbody > tr > .total-price  > .product-line-price").each(function (t) {
        e = parseFloat(e) + parseFloat($(this).text().replace(/[^\d\.]/g, ""))
    }), $("#final_total").text(e.toFixed(2))
}

function display_compare() {
    var i = localStorage.getItem("compare"), i = null !== localStorage.getItem("compare") ? i : null;
    $.ajax({
        type: "POST",
        url: base_url + "compare/add_to_compare",
        data: {product_id: i, product_variant_id: i, [csrfName]: csrfHash},
        dataType: "json",
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash;
            var e = i.length || "base_url()";
            $("#compare_count").text(t.data.total);
            var l = "";
            0 == t.error ? (null !== i && 0 < e && (l += '<div class="text-right"><div class="compare-removal"><button class="remove-compare button button-danger" >Clear Compare</button></div></div></div><table class="compare-table"><tbody><tr><th class="compare-field"> </th>', $.each(t.data.product, function (t, e) {
                var i, n,
                    o = 0 < e.variants[0].special_price && "" != e.variants[0].special_price ? e.variants[0].special_price : e.variants[0].price,
                    s = e.minimum_order_quantity || 1,
                    a = e.minimum_order_quantity && e.quantity_step_size ? e.quantity_step_size : 1,
                    r = e.total_allowed_quantity || 1;
                l += '<td class="compare_item text-center text-justify"><div class="text-right"><a class="remove-compare-item"data-product-id="' + e.id + '" style="padding: 4px 8px border:0px !important" ><i class="fa-times fa-times-plus fa-lg fa link-color"></i></a></div><br><div class="product-grid" style="border:1px !important; padding:0 0 0px;"><div class="product-image"><div class="product-image-container"><a href="products/details/' + e.slug + '"><img class="pic-1" src="' + e.image + '"></a></div></div><div itemscope itemtype="https://schema.org/Product">', e.rating && "" != e.no_of_rating ? l += '<div class="col-md-12 mb-3 product-rating-small" dir="ltr" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"><meta itemprop="reviewCount" content="' + e.no_of_rating + '" /><meta itemprop="ratingValue" content="' + e.rating + '" /><input type="text" class="kv-fa rating-loading" value="' + e.rating + '" data-size="sm" title="" readonly> <span class="my-auto mx-3"> ( ' + e.no_of_ratings + " reviews) </span></div>" : l += '<div class="col-md-12 mb-3 product-rating-small" dir="ltr"><input type="text" class="kv-fa rating-loading" value="' + e.rating + '" data-size="sm" title="" readonly> <span class="my-auto mx-3"> ( ' + e.no_of_ratings + " reviews) </span></div>", l += "</div>", l += ' <h3 class="data-product-title" ><a href="products/details/' + e.slug + '">' + e.name + '</a></h3>   <div class="price mb-1">' + currency + " " + ("simple_product" == e.type ? e.variants[0].price : e.min_max_price.max_special_price + " - " + e.min_max_price.max_price) + " </div>", n = "simple_product" == e.type ? (i = e.variants[0].id, "") : (i = "", "#quick-view"), l += '  <a href="#" class="add-to-cart add_to_cart" data-product-id="' + e.id + '" data-product-variant-id="' + i + '" data-izimodal-open="' + n + '" data-product-title="' + e.name + '" data-product-image="' + e.image + '" data-product-description="' + e.short_description + '"  data-product-price="' + o + '" data-min="' + s + '" data-max="' + r + '" data-step="' + a + '"><i class="fas fa-cart-plus"></i> Add to Cart</a>'
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Description </th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="Availability">' + (e.short_description || (e.short_description = "-")) + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">variants </th>', $.each(t.data.product, function (t, e) {
                var i = e.variants[0].attr_name.split(","), n = e.variants[0].variant_values.split(",");
                if ("variable_product" == e.type) {
                    l += '<td class="text-center text-justify" data-title="variants">';
                    for (t = 0; t < i.length; t++) i[t] !== n[t] && (l += i[t] + " : " + n[t] + "<br>");
                    l += "</td>"
                } else l += '<td class="text-center text-justify" data-title="variants">-</td>'
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Availability </th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="Availability">' + ("1" == e.availability ? e.availability = "In Stock" : e.availability = "-") + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Made In </th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="made in">' + (e.made_in || "-") + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Warranty</th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="warranty period">' + (e.warranty_period || "-") + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Gurantee</th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="warranty period">' + (e.guarantee_period || "-") + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Returnable</th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="Returnable">' + ("1" == e.is_returnable ? e.is_returnable = "Yes" : e.is_returnable = "No") + "</td>"
            }), l += "</tr>", l += '<tr><th class="compare-field text-center text-justify">Cancelable</th>', $.each(t.data.product, function (t, e) {
                l += '<td class="text-center text-justify" data-title="cancelable">' + ("1" == e.is_cancelable ? e.is_cancelable = "Yes" : e.is_cancelable = "No") + "</td>"
            }), l += "</tr>", l += "</tbody></table>"), $("#compare-items").html(l), $(".kv-fa").rating({
                theme: "krajee-fa",
                filledStar: '<i class="fas fa-star"></i>',
                emptyStar: '<i class="far fa-star"></i>',
                showClear: !1,
                showCaption: !1,
                size: "md"
            })) : Toast.fire({icon: "error", title: t.message})
        }
    })
}

$(document).ready(function () {
    navigator.geolocation && navigator.geolocation.getCurrentPosition(function (t) {
        var e = t.coords.latitude, t = t.coords.longitude;
        sessionStorage.setItem("latitude", e), sessionStorage.setItem("longitude", t)
    }, function (t) {
        switch (t.code) {
            case t.PERMISSION_DENIED:
                null !== sessionStorage.getItem("latitude") && sessionStorage.removeItem("latitude"), null !== sessionStorage.getItem("longitude") && sessionStorage.removeItem("longitude");
                break;
            case t.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.");
                break;
            case t.TIMEOUT:
                console.log("The request to get user location timed out.");
                break;
            case t.UNKNOWN_ERROR:
                console.log("An unknown error occurred.")
        }
    })
}), $("#send_bank_receipt_form").on("submit", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST", url: $(this).attr("action"), data: t, beforeSend: function () {
            $("#submit_btn").html("Please Wait..").attr("disabled", !0)
        }, cache: !1, contentType: !1, processData: !1, dataType: "json", success: function (t) {
            csrfHash = t.csrfHash, $("#submit_btn").html("Send").attr("disabled", !1), 0 == t.error ? ($("table").bootstrapTable("refresh"), Toast.fire({
                icon: "success",
                title: t.message
            }), window.location.reload()) : Toast.fire({icon: "error", title: t.message})
        }
    })
}), $(document).ready(function () {
    $(".hrDiv").length && ($(".hrDiv p").addClass("hrDiv"), $("div").css({"font-size": "", font: ""}))
}), $("#validate-zipcode-form").on("submit", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        url: base_url + "products/check_zipcode",
        data: t,
        beforeSend: function () {
            $("#validate_zipcode").html("Please Wait..").attr("disabled", !0)
        },
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        success: function (t) {
            csrfHash = t.csrfHash, $("#validate_zipcode").html("Check Availability").attr("disabled", !1), 0 == t.error ? $("#add_cart").removeAttr("disabled") : $("#add_cart").attr("disabled", "true"), $("#error_box").html(t.message)
        }
    })
}), $(document).on("submit", ".validate_zipcode_quick_view", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "post",
        url: base_url + "products/check-zipcode",
        data: t,
        beforeSend: function () {
            $("#validate_zipcode").html("Please Wait..").attr("disabled", !0)
        },
        cache: !1,
        contentType: !1,
        processData: !1,
        dataType: "json",
        success: function (t) {
            csrfHash = t.csrfHash, $("#validate_zipcode").html("Check Availability").attr("disabled", !1), 0 == t.error ? $("#modal-add-to-cart-button").removeAttr("disabled") : $("#modal-add-to-cart-button").attr("disabled", "true"), $("#error_box1").html(t.message)
        }
    })
}), $(".view_cart_button").click(function () {
    return 0 != is_loggedin || ($("#modal-custom").iziModal("open"), $("#login_div").removeClass("hide"), $("#login").addClass("active"), $("#register_div").addClass("hide"), $("#register").removeClass("active"), !1)
}), window.editAddress = {
    "click .edit-address": function (t, e, i, n) {
        $("#address_id").val(i.id), $("#edit_name").val(i.name), $("#edit_mobile").val(i.mobile), $("#edit_address").val(i.address), $("#edit_state").val(i.state), $("#edit_country").val(i.country), $("#edit_city").val(i.city_id).trigger("change", [i.area_id]), $("#edit_area").val(i.area_id).trigger("change", [i.area_id]), $("input[type=radio][value=" + i.type.toLowerCase() + "]").attr("checked", !0)
    }
}, $(document).ready(function () {
    var t;
    !localStorage.getItem("compare") || (t = null !== (t = localStorage.getItem("compare").length) ? JSON.parse(t) : null) && display_compare()
}), $(document).on("click", ".compare", function (t) {
    t.preventDefault();
    var e = $(this).attr("data-product-id"), i = $(this).attr("data-product-variant-id"),
        t = {product_id: e.trim(), product_variant_id: i.trim()}, i = localStorage.getItem("compare");
    if (Toast.fire({
        icon: "success",
        title: "products added to compare list"
    }), null != (i = null !== i ? JSON.parse(i) : null)) {
        if (i.find(t => t.product_id === e)) return void Toast.fire({
            icon: "error",
            title: "This item is already present in your compare list"
        });
        i.push(t)
    } else i = [t];
    localStorage.setItem("compare", JSON.stringify(i));
    t = i.length || "";
    if ($("#compare_count").text(t), null !== i && t <= 1) return Toast.fire({
        icon: "error",
        title: "Please select 1 more item to compare"
    }), !1
}), $(document).on("click", ".remove-compare-item", function (t) {
    t.preventDefault();
    var e = $(this).attr("data-product-id");
    confirm("Are you sure want to remove this?") && (t = $("#compare_count").text(), t--, $("#compare_count").text(t), t < 1 ? ($(this).parent().parent().remove(), location.reload()) : $(this).parent().parent().remove(), (t = null !== (t = localStorage.getItem("compare")) ? JSON.parse(t) : null) && (t = t.filter(function (t) {
        return t.product_id != e
    }), localStorage.setItem("compare", JSON.stringify(t)), display_compare()))
}), $(document).on("click", ".compare-removal button", function (t) {
    t.preventDefault();
    var e = $(this).attr("data-product-id"), i = $(this).parent().parent().parent();
    confirm("Are you sure want to remove this?") && (localStorage.removeItem("compare"), location.reload(), i = localStorage.getItem("compare"), (i = null !== localStorage.getItem("compare") ? JSON.parse(i) : null) && (t = i.filter(function (t) {
        return t.id != e
    }), localStorage.setItem("compare", JSON.stringify(t)), i && display_compare(t)))
}), $(document).on("submit", "#add-faqs", function (t) {
    t.preventDefault();
    t = new FormData(this);
    t.append(csrfName, csrfHash), $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        dataType: "json",
        data: t,
        processData: !1,
        contentType: !1,
        success: function (t) {
            csrfName = t.csrfName, csrfHash = t.csrfHash, 0 == t.error ? (Toast.fire({
                icon: "success",
                title: t.message
            }), $("#add-faqs")[0].reset()) : Toast.fire({icon: "error", title: t.message}), setTimeout(function () {
                location.reload()
            }, 1e3)
        }
    })
}), $(".search_faqs").select2({
    ajax: {
        url: base_url + "products/get_faqs_data",
        type: "GET",
        dataType: "json",
        delay: 250,
        data: function (t) {
            return {search: t.term}
        },
        processResults: function (t) {
            return {results: t}
        },
        cache: !0
    }, minimumInputLength: 1, theme: "bootstrap4", placeholder: "Search for faqs"
}), $(function () {
    $("#inspect_value").data("value");
    return !1
});
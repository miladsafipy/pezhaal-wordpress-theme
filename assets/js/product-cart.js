
document.addEventListener('DOMContentLoaded', function () {

    const forms = document.querySelectorAll(
        '.product-card__cart-form'
    );

    forms.forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const button = form.querySelector(
                '.product-card__add-to-cart'
            );

            
            const productId = button.value;

            const quantityInput = form.querySelector(
                'input[name="quantity"]'
            );

            const quantity = quantityInput
                ? parseInt(quantityInput.value, 10)
                : 1;
            

            if (
                !productId ||
                !Number.isInteger(quantity) ||
                quantity < 1
            ) {
                return;
            }

            

            // جلوگیری از کلیک چندباره
            button.disabled = true;

            const originalText = button.textContent;

            button.textContent = 'در حال افزودن...';

            try {

                const response = await fetch(
                    PezhaalCart.ajaxUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/x-www-form-urlencoded'
                        },

                        body: new URLSearchParams({
                            action: 'pezhaal_add_to_cart',
                            product_id: productId,
                            quantity: quantity,
                            nonce: PezhaalCart.nonce
                        })
                    }
                );

                const result = await response.json();

                if (!result.success) {
                    throw new Error(
                        result.data.message || 'خطا در افزودن محصول'
                    );
                }

                // دریافت تعداد انواع محصولات
                const cartCount = result.data.cart_count;

                // به‌روزرسانی تمام Badgeهای موجود در صفحه
                document.querySelectorAll('.cart-count').forEach(function (badge) {

                    badge.textContent = cartCount;

                    badge.classList.toggle(
                        'is-visible',
                        cartCount > 0
                    );

                });

                // نمایش پیام موفقیت
                button.textContent = 'اضافه شد ✓';

                // اجرای رویداد استاندارد ووکامرس
                if (window.jQuery) {

                    jQuery(document.body).trigger(
                        'added_to_cart',
                        [
                            result.data.fragments,
                            result.data.cart_hash,
                            jQuery(button)
                        ]
                    );

                }

                setTimeout(function () {

                    button.textContent = originalText;
                    button.disabled = false;

                }, 1500);

            } catch (error) {

                console.error(error);

                button.textContent = 'خطا!';

                setTimeout(function () {

                    button.textContent = originalText;
                    button.disabled = false;

                }, 1500);

            }

        });

    });

});









/**
 * Update cart item quantity using AJAX.
 */

document.addEventListener('click', async function (event) {

    const button = event.target.closest('.cart-item-update');

    if (!button) {
        return;
    }

    const cartItem = button.closest('.cart-item');

    const quantityInput = cartItem.querySelector(
        '.cart-item-quantity-input'
    );

    const cartItemKey = cartItem.dataset.cartItemKey;

    const quantity = parseInt(quantityInput.value, 10);


    // Validate quantity.
    if (!quantity || quantity < 1) {

        alert('لطفاً تعداد معتبر وارد کنید.');

        return;
    }


    // Prevent multiple requests.
    button.disabled = true;

    const originalText = button.textContent;

    button.textContent = 'در حال بروزرسانی...';


    // Prepare AJAX data.
    const formData = new URLSearchParams();

    formData.append(
        'action',
        'pezhaal_update_cart_item'
    );

    formData.append(
        'cart_item_key',
        cartItemKey
    );

    formData.append(
        'quantity',
        quantity
    );

    formData.append(
        'nonce',
        PezhaalCart.nonce
    );


    try {

        const response = await fetch(
            PezhaalCart.ajaxUrl,
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },

                body: formData
            }
        );


        const result = await response.json();


        if (!result.success) {

            alert(
                result.data.message ||
                'خطایی هنگام به‌روزرسانی رخ داد.'
            );

            return;
        }


        // Update product subtotal.
        cartItem.querySelector(
            '.cart-item-subtotal'
        ).innerHTML = result.data.item_subtotal;


        // Update cart subtotal.
        document.querySelector(
            '.cart-subtotal strong'
        ).innerHTML = result.data.cart_subtotal;


        // Update final total.
        document.querySelector(
            '.cart-total strong'
        ).innerHTML = result.data.cart_total;


        // Success message.
        button.textContent = 'به‌روزرسانی شد';

    }


    catch (error) {

        console.error('Cart update error:', error);

        alert('ارتباط با سرور برقرار نشد.');

    }


    finally {

        button.disabled = false;

        setTimeout(function () {

            button.textContent = originalText;

        }, 1500);

    }

});
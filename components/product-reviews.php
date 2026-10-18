
<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
    $product = wc_get_product( get_queried_object_id() );
}

if ( ! $product ) {
    return;
}

$product_id = $product->get_id();

$reviews = get_comments( [
    'post_id' => $product_id,
    'status'  => 'approve',
    'type'    => 'review',
    'orderby' => 'comment_date_gmt',
    'order'   => 'DESC',
] );

// Convert Gregorian date to Persian calendar if PHP Intl is enabled.
if ( ! function_exists( 'pezhaal_persian_date' ) ) {
    function pezhaal_persian_date( $date ) {
        if ( class_exists( 'IntlDateFormatter' ) ) {
            $formatter = new IntlDateFormatter(
                'fa_IR@calendar=persian',
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::NONE,
                wp_timezone(),
                IntlDateFormatter::TRADITIONAL,
                'yyyy/MM/dd'
            );

            return $formatter->format( $date );
        }

        return wp_date( 'Y/m/d', $date, wp_timezone() );
    }
}
?>

<section class="product-reviews" id="product-reviews">

    <div class="product-reviews__header">
        <div>
            <h4>نظرات مشتریان</h4>
            <p>
                <?php echo esc_html( $product->get_review_count() ); ?>
                نظر ثبت شده
            </p>
        </div>

        <div class="product-reviews__average">
            <?php echo wp_kses_post( wc_get_rating_html(
                $product->get_average_rating(),
                $product->get_rating_count()
            ) ); ?>

            <span>
                <?php echo esc_html(
                    number_format_i18n(
                        (float) $product->get_average_rating(),
                        1
                    )
                ); ?>
                از ۵
            </span>
        </div>
    </div>

    <div class="reviews-carousel">

        <button
            class="reviews-carousel__button reviews-carousel__button--next"
            type="button"
            aria-label="نظرات بعدی"
        >
            &lt;
             
        </button>

        <div class="reviews-carousel__viewport">
            <div class="reviews-carousel__track">

                <?php if ( $reviews ) : ?>

                    <?php foreach ( $reviews as $review ) :
                        $rating = (int) get_comment_meta(
                            $review->comment_ID,
                            'rating',
                            true
                        );
                        $timestamp = get_comment_time(
                            'U',
                            true,
                            false,
                            $review
                        );
                    ?>

                        <article class="review-card">

                            <div class="review-card__header">

                                <strong class="review-card__author">
                                    <?php echo esc_html(
                                        $review->comment_author
                                    ); ?>
                                </strong>

                                <time class="review-card__date">
                                    <?php
                                    echo esc_html(
                                        pezhaal_persian_date(
                                            (int) $timestamp
                                        )
                                    );
                                    ?>
                                </time>

                            </div>

                            <div
                                class="review-card__rating"
                                aria-label="<?php
                                echo esc_attr(
                                    sprintf(
                                        'امتیاز %d از ۵',
                                        $rating
                                    )
                                );
                                ?>"
                            >
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <span class="<?php
                                        echo $i <= $rating
                                            ? 'is-filled'
                                            : '';
                                    ?>">★</span>
                                <?php endfor; ?>
                            </div>

                            <div class="review-card__content">
                                <?php echo wp_kses_post(
                                    wpautop( $review->comment_content )
                                ); ?>
                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php else : ?>

                    <p class="reviews-empty">
                        هنوز نظری برای این محصول ثبت نشده است.
                        اولین نفری باشید که نظر می‌دهد.
                    </p>

                <?php endif; ?>

            </div>
        </div>

        <button
            class="reviews-carousel__button reviews-carousel__button--prev"
            type="button"
            aria-label="نظرات قبلی"
        >
            &gt;
        </button>

        

    </div>






    
    <?php if ( comments_open( $product_id ) ) : ?>

        <div class="product-reviews__form">

            <h3>نظر شما درباره این محصول چیست؟</h3>

            <?php
            $rating_enabled = function_exists( 'wc_review_ratings_enabled' )
                && wc_review_ratings_enabled();

            $rating_required = function_exists( 'wc_review_rating_required' )
                && wc_review_rating_required();

            $comment_field = '';

            // Add WooCommerce's rating field.
            if ( $rating_enabled ) {
                $comment_field .=
                    '<p class="comment-form-rating">
                        <label for="rating">امتیاز شما';

                if ( $rating_required ) {
                    $comment_field .= ' <span class="required">*</span>';
                }

                $comment_field .=
                    '</label>
                    <select name="rating" id="rating" '
                    . ( $rating_required ? 'required' : '' ) . '>
                        <option value="">انتخاب امتیاز</option>
                        <option value="5">عالی - ۵ ستاره</option>
                        <option value="4">خوب - ۴ ستاره</option>
                        <option value="3">متوسط - ۳ ستاره</option>
                        <option value="2">ضعیف - ۲ ستاره</option>
                        <option value="1">خیلی ضعیف - ۱ ستاره</option>
                    </select>
                    </p>';
            }

            // Add the review text field.
            $comment_field .=
                '<p class="comment-form-comment">
                    <label for="comment">متن نظر</label>
                    <textarea
                        id="comment"
                        name="comment"
                        rows="5"
                        required
                    ></textarea>
                </p>';

            $review_form_args = [
                'title_reply'          => '',
                'label_submit'         => 'ثبت نظر',
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
                'logged_in_as'         => '',
                'comment_field'        => $comment_field,
                'class_form'           => 'pezhaal-review-form',
            ];

            comment_form(
                apply_filters(
                    'woocommerce_product_review_comment_form_args',
                    $review_form_args
                ),
                $product_id
            );
            ?>

        </div>

    <?php else : ?>

        <p class="reviews-closed">
            ثبت نظر برای این محصول غیرفعال است.
        </p>

    <?php endif; ?>


</section>

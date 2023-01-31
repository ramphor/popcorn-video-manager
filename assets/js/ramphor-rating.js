function ramphor_start_update_rate() {
}
function ramphor_end_update_rate() {
}

function ramphor_set_star_rating(rating, done = ramphor_end_update_rate) {
    // var testimonial_rating; This is global variable
    ramphor_rating_global = window.ramphor_rating_global || {};
    if (!ramphor_rating_global.set_rate_url || !ramphor_rating_global.post_id) {
        return;
    }
    ramphor_start_update_rate();
    tmpRating = ramphor_popcorn_rating.getRating();
    ramphor_popcorn_rating.setRating(rating);

    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            response = JSON.parse(xhr.response);
            if (!response.success) {
                ramphor_popcorn_rating.setRating(tmpRating);
            }
        } else {
            ramphor_popcorn_rating.setRating(tmpRating);
        }
        if (typeof done === 'function') {
            done();
        }
    }

    xhr.open('POST', ramphor_rating_global.set_rate_url);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        rating: rating,
        post_id: ramphor_rating_global.post_id,
        nonce: ramphor_rating_global.current_nonce,
    }));
}
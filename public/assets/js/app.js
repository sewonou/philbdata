$(document).on('change', '#district_town, #district_prefecture', function () {
    let $field = $(this);

    let $townField = $('#district_town');
    let $form = $field.closest('form');
    let target = '#' + $field.attr('id').replace('prefecture', 'township').replace('town', 'prefecture');
    let data = {};

    data[$townField.attr('name')] = $townField.val();
    data[$field.attr('name')] = $field.val();

    $.post($form.attr('action'), data).then(function (data) {
        let $input = $(data).find(target);
        $(target).replaceWith($input);
    })
});

$(document).on('change', '#township_region, #township_prefecture', function () {
    let $field = $(this);

    let $regionField = $('#township_region');

    let $form = $field.closest('form');
    let target = '#' + $field.attr('id').replace('prefecture', 'town').replace('region', 'prefecture');
    let data = {};
    data[$regionField.attr('name')] = $regionField.val();
    data[$field.attr('name')] = $field.val();

    $.post($form.attr('action'), data).then(function (data) {
        let $input = $(data).find(target);
        $(target).replaceWith($input);
    })
});
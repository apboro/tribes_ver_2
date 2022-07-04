export const service_message = {
    success: 'Success',
    info: 'Information',
    warning: 'Warning',
    error: 'Error',
    please_later: 'Please try again later.',
    please_feedback: 'Please report this issue to us.',
    please_auth: 'Please log in to the system.',

    // Success
    success_add_community: 'Community added successfully. To go to it, click on the "Go to management" button',
    command_copied: 'Command copied',
    success_copy: 'Copied',

    // Info
    select_image_area: 'Select the desired area of ​​the image',
    redirect_message: 'You will be redirected to the community dashboard via',

    // Warning
    max_size_img: 'Image size exceeds 2MB',
    limit_width_ratio_img: 'The ratio of the image width to its height exceeds the allowable norm',
    limit_height_ratio_img: 'The ratio of the image height to its width exceeds the allowable norm',
    min_size_img: 'Image size must be over 300*300px',
    none_tariff_period: 'If you remove the trial period, then all members who have not paid the tariff will be automatically removed from your community after 10 minutes',

    // Error
    400: 'The server cannot accept the request.',
    401: 'You are not logged in.',
    403: 'You do not have access rights.',
    404: 'The server cannot find the requested resource.',
    405: 'The server cannot find the requested resource.',
    408: 'The connection has been terminated.',
    409: 'The server cannot accept the request.',
    410: 'The requested content is missing.',
    411: 'The server cannot accept the request.',
    412: 'The server cannot accept the request.',
    413: 'The server cannot accept the request.',
    414: 'The server cannot accept the request.',
    415: 'The server cannot accept the request.',
    416: 'The server cannot accept the request.',
    417: 'The server cannot accept the request.',
    500: 'Server is not available.',
    501: 'The request cannot be processed by the server.',
    502: 'The server received an invalid response.',
    503: 'Service is unavailable.',
    504: 'The server did not receive a response.',
};

export function getServiceMessage() {
    return service_message;
}

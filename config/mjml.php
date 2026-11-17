<?php
/**
 * MJML plugin settings
 *
 * Emails (templates/_special/emails) are written in MJML and compiled with
 * the mjml binary from node_modules (`npm install` on every server).
 * The paths come from the environment, since Node lives in a different
 * place on each machine:
 *
 *   NODE_PATH  e.g. /usr/local/bin/node
 *   MJML_PATH  e.g. /var/www/html/node_modules/mjml/bin/mjml
 *
 * Same names as on the other sites of the hosting account. (Node itself
 * also reads NODE_PATH, as a list of module folders; a path to the binary
 * there is ignored, so the mjml process is not affected.)
 *
 * @link https://github.com/sjelfull/craft3-mjml
 */

return [
    'nodePath' => '$NODE_PATH',
    'mjmlCliPath' => '$MJML_PATH',
    'mjmlCliConfigArgs' => '--config.validationLevel=strict',
];

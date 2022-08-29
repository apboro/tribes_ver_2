#!/bin/sh

auto_envsubst() {
  local template_dir="${NGINX_ENVSUBST_TEMPLATE_DIR:-/etc/nginx/templates}"
  local suffix="${NGINX_ENVSUBST_TEMPLATE_SUFFIX:-.template}"
  local output_dir="${NGINX_ENVSUBST_OUTPUT_DIR:-/etc/nginx/configs}"

  local template defined_envs relative_path output_path subdir
  defined_envs=$(printf '${%s} ' $(env | cut -d= -f1))
  [ -d "$template_dir" ] || return 0
  find "$template_dir" -follow -type f -name "*$suffix" -print | while read -r template; do
    relative_path="${template#$template_dir/}"
    output_path="$output_dir/${relative_path%$suffix}"
    subdir=$(dirname "$relative_path")
    # create a subdirectory where the template file exists
    mkdir -p "$output_dir/$subdir"
    echo "Running envsubst on $template to $output_path"
    envsubst "$defined_envs" < "$template" > "$output_path"
  done
}

command="$1"

case "$command" in
  "php-fpm" )
    php artisan config:cache
    php artisan route:cache
    php-fpm -c /etc/php7/php.ini
  ;;
  "frontend" | "proxy" )
    ln -sf /dev/stdout /var/log/nginx/access.log
    ln -sf /dev/stderr /var/log/nginx/error.log
    auto_envsubst
    conf_file="$command.conf"
    conf_source="/etc/nginx/configs"
    conf_target="/etc/nginx/http.d"
    [ ! -e "$conf_source/$conf_file" ] && echo "No configuration file found for $command mode" && exit 1
    ln "$conf_source/$conf_file" "$conf_target/$conf_file"
    nginx -g "daemon off;"
  ;;
  * )
    php $@
  ;;
esac
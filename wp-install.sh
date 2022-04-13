#!/bin/bash -e

#install redis plugin
wpInstallRedis()
{
if ! wp plugin is-installed redis-cache; then
    wp plugin install redis-cache
    #config redis plugin
    wp config set WP_REDIS_HOST redis
    wp config set WP_REDIS_PORT 6379 --raw
    wp config set WP_REDIS_TIMEOUT 1 --raw
    wp config set WP_REDIS_READ_TIMEOUT 1 --raw
    wp config set WP_REDIS_DATABASE 0 --raw
    #active redis plugin
    wp plugin activate redis-cache
    #enable redis plugin
    wp redis enable
fi
}
wpInstallPlugin()
{
    pluginName=$1
    pluginPack=$2
    echo "Plugin ${pluginName}"
    if [ ! -z "$pluginName" ]; then
        if ! wp plugin is-installed ${pluginName}; then
            wp plugin install ${pluginPack} --force
        fi
        if ! wp plugin is-active ${pluginName}; then
            wp plugin activate ${pluginName};
        fi
    fi
}
wpInstallTheme()
{
    themeName=$1
    themePack=$2
    echo "Plugin ${themeName}"
    if [ ! -z "$themePack" ]; then
        if !  wp theme is-installed ${themeName}; then
            wp theme install ${themePack}
        fi
        if ! wp theme is-active ${themePack}; then
            wp theme activate ${themePack}
        fi
    fi
}

wpInstall()
{
    wpComponentType=$1
    wpComponentList=;
    if [ ${wpComponentType} == "plugin" ]; then
        wpComponentList=($(echo "${plugin}" | tr " " "\n"))
    else
        wpComponentList=($(echo "${theme}" | tr " " "\n"))
    fi
    for wpComponent in "${wpComponentList[@]}"
    do
        key=;
        pack=;
        if [[ ${wpComponent} == *=* ]]; then
            wpc=($(echo $wpComponent | tr "=" "\n"))
            key=${wpc[0]}
            pack=${wpc[1]}
        else
            key=${wpComponent}
            pack=${wpComponent}
        fi
        case ${pack} in
            "redis-cache")
                wpInstallRedis  ;;
            *)
                if [ ${wpComponentType} == "plugin" ]
                then
                    wpInstallPlugin ${key} ${pack}
                else
                    wpInstallTheme ${key} ${pack}
                fi
                ;;
        esac            
    done
}

#install
if ! wp core is-installed; then
    echo "install WP"
    wp core install --path="/var/www/html" --url="http://localhost" --title=${SITE_TITLE} --admin_user=${SITE_ADMIN_USER} --admin_password=${SITE_ADMIN_PASSWORD} --admin_email=${SITE_ADMIN_EMAIL} --skip-email;
    sleep 30;
fi

#install plugins
wpInstall "plugin"

#install themes
wpInstall "theme"
#!/usr/bin/env bash
set -o nounset
set -o errexit

IFS='.' read -r -a VERSION_ARRAY <<< "$(git tag --sort=-committerdate | head -n 1)"

printf -v MAJOR '%d' "${VERSION_ARRAY[0]}" 2>/dev/null
printf -v MINOR '%d' "${VERSION_ARRAY[1]}" 2>/dev/null
printf -v PATCH '%d' "${VERSION_ARRAY[2]}" 2>/dev/null

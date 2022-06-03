#!/usr/bin/env bash
set -o nounset
set -o errexit

function ensure_git_repository()
{
    if [[ ! -d ".git" ]]; then
        cecho "This directory is not a valid git repository." 31
        exit 1
    fi

    if [[ ! -e ".git/HEAD" ]] || [[ ! -e ".git/config" ]]; then
        cecho "This directory is not a valid git repository." 31
        ensure_correct_repo
        exit 1
    fi
}

function current_branch()
{
    ensure_git_repository
    git rev-parse --abbrev-ref HEAD
}

function ensure_branch()
{
    ensure_git_repository
    TARGET_BRANCH="${1}"
    CURRENT_BRANCH="$(current_branch)"
    if [[ "${CURRENT_BRANCH}" != "${TARGET_BRANCH}" ]]; then
        cecho "Unexpected branch. Expected \"${TARGET_BRANCH}\" got \"${CURRENT_BRANCH}\"." 31
        exit 1
    fi
}

function last_commit()
{
    ensure_git_repository
    git log --oneline -n 1
}

function last_commit_hash()
{
    ensure_git_repository
    git rev-parse HEAD
}

function ensure_reset_branch() {
    TARGET_BRANCH="${1}"
    git fetch origin
    git checkout "${TARGET_BRANCH}" --force
    ensure_branch "${TARGET_BRANCH}"
    git reset --hard "origin/${TARGET_BRANCH}"
    git clean -d --force
    git pull
}